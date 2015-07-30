<?php
namespace app\controllers;

use app\models\User;
use app\models\UserRole;
use Yii;
use yii\helpers\StringHelper;
use yii\web\Controller;
use app\form\LoginForm;
use app\form\PasswordReminderForm;
use app\form\ChangePasswordForm;
use app\models\Auth;
use app\models\MyAuthClient;



class LoginController extends Controller
{

    public $rememberMe = true;

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post())) {
            //there is where user is going where he logIn succesfull
            $model = User::findOne(['email' => $form->username]);
            if($model == null) {
                $model = User::findOne(['login' => $form->username]);
            }
            if($model != null) {
                if ($model->token == '' || $model->token == null) {
                    if($form->login()) {
                        Yii::$app->session->setFlash('logged', Yii::t('app', 'You are now logged'));
                        return $this->refresh();
                    } else {
                        Yii::$app->session->setFlash('notCorrect', Yii::t('app', 'Bad login or password'));
                        $this->refresh();
                    }
                } else {
                    Yii::$app->session->setFlash('notActivated', Yii::t('app', 'You account is not activated'));
                    return $this->render('login', [
                        'form' => $form,
                    ]);
                }
            } else {
                Yii::$app->session->setFlash('notFoundAccount', Yii::t('app', 'There is no user with this name'));
                $this->refresh();
            }
        } else {
            return $this->render('login', [
                'form' => $form,
            ]);
        }
    }

    public function actionLogged(){
        return $this->goHome();
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionPasswordreminder()
    {
        $model = new PasswordReminderForm();
        $token = md5(rand(100000000000000,500000000000000));
        if ($model->load(Yii::$app->request->post()) && $model->sendMessage($token)&&$model->dbSaveToken($token)) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('passwordreminder', [
                'model' => $model,
            ]);
        }
    }

    public function actionLoginByFacebook(){
        $id = Yii::$app->request->get('id');
        $user = User::findOne(['facebook_id' => $id]);
        if($user == null){
            $user = new User();
            $user->login = Yii::$app->request->get('name');
            $user->facebook_id = Yii::$app->request->get('id');
            $user->email = StringHelper::internationFormatString(Yii::$app->request->get('name')) . "@change.me";
            $user->user_role=0;
            $user->pass = hash('sha256',Yii::$app->params['hashSalt'].md5(rand(100000000000000,500000000000000)));
            $user->save();
        }
        $this->loginToSystemByFacebook($user);
        $this->goHome();
    }

    private function loginToSystemByFacebook($user){
        return $user == null ? null : Yii::$app->user->login($user, $this->rememberMe ? Yii::$app->params['cookieTime'] : 0);

    }
    public function actionChangepassword()
    {
        $model = new ChangePasswordForm();
        $request = Yii::$app->request;
        $token = $request->get('key');

        if ($token==null || User::findOne(['token' => $token])==null) {
            return $this->goHome();
        }

        if ($model->load(Yii::$app->request->post()) && $model->changePassword($token)) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        } else if($model->load(Yii::$app->request->post()) && !$model->changePassword($token)) {
            return $this->goHome();
        } else {
            return $this->render('changepassword', [
                'model' => $model,
            ]);
        }
    }
    public function actionTweeterlogin()
    {
        $twitter = Yii::$app->twitter->getTwitter();
        $request_token = $twitter->getRequestToken();

        //set some session info
        Yii::$app->session['oauth_token'] = $token =           $request_token['oauth_token'];
        Yii::$app->session['oauth_token_secret'] = $request_token['oauth_token_secret'];

        if($twitter->http_code == 200){
            //get twitter connect url
            $url = $twitter->getAuthorizeURL($token);
            //send them
            $this->redirect($url);
        }else{
            //error here
            $this->redirect(Yii::$app->homeUrl);
        }
    }

    public function actionTweetercallback() {
        /* If the oauth_token is old redirect to the connect page. */
        if (isset($_REQUEST['oauth_token']) && Yii::$app->session['oauth_token'] !== $_REQUEST['oauth_token']) {
            Yii::$app->session['oauth_status'] = 'oldtoken';
        }

        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
        $twitter = Yii::$app->twitter->getTwitterTokened(Yii::$app->session['oauth_token'], Yii::$app->session['oauth_token_secret']);

        /* Request access tokens from twitter */
        $access_token = $twitter->getAccessToken($_REQUEST['oauth_verifier']);

        /* Save the access tokens. Normally these would be saved in a database for future use. */
        Yii::$app->session['access_token'] = $access_token;

        /* Remove no longer needed request tokens */
        unset(Yii::$app->session['oauth_token']);
        unset(Yii::$app->session['oauth_token_secret']);

        if (200 == $twitter->http_code) {
            /* The user has been verified and the access tokens can be saved for future use */
            Yii::$app->session['status'] = 'verified';

            //get an access twitter object
            $twitter = Yii::$app->twitter->getTwitterTokened($access_token['oauth_token'],$access_token['oauth_token_secret']);

            //get user details
            $twuser= $twitter->get("account/verify_credentials");
            //get friends ids
            //$friends= $twitter->get("friends/ids");
            //get followers ids
            //$followers= $twitter->get("followers/ids");
            //tweet
            //$result=$twitter->post('statuses/update', array('status' => "Tweet message"));



            $user = User::find()
                ->where(['twitter_id' => $twuser->id ])
                ->one();

            if($user==null) {
                $newuser = new User();
                $newuser->twitter_id=$twuser->id;
                $newuser->login=$twuser->screen_name;
                $password=substr( md5(rand()), 0, 7);
                $newuser->pass=hash('sha256',Yii::$app->params['hashSalt'].$password);
                $newuser->email=$twuser->screen_name.'@change.me';
                $newuser->user_role=0;
                $newuser->save();
                Yii::$app->user->login($newuser, $this->rememberMe ? Yii::$app->params['cookieTime'] : 0);
            } else {
                Yii::$app->user->login($user, $this->rememberMe ? Yii::$app->params['cookieTime'] : 0);
            }
            Yii::$app->session->setFlash('logged');
            return $this->render('tweetercallback',['twuser' => $twuser,]);

        } else {
            /* Save HTTP status for error dialog on connnect page.*/
            //header('Location: /clearsessions.php');
            //$this->redirect(Yii::$app->homeUrl);
        }
    }

}