<?php

namespace app\form;

use app\components\PasswordHelper;
use Yii;
use yii\base\Model;
use app\models\User;
use app\validators\LoginValidator;
use yii\validators\EmailValidator;

class RegisterForm extends User
{
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [                // verifyCode needs to be entered correctly
                ['verifyCode', 'captcha'],
            ]
        );

    }

    /**
     * @return array customized attribute labels
     */
    public function validateLogin()
    {
        $user = User::findOne(['login'=>$this->login]);
        if ($user!=null) {
            $this->addError('login', 'Login już istnieje');
        }
    }

    public function getToken(){
        $token = md5(rand(100000000000000,500000000000000));
        return $token;
    }

    public function saveToken($token){
        $user = User::find()
            ->where(['email' => $this->email])
            ->one();
        if($user != null) {
            $user->token=$token;
            $user->save();
            return true;
        } else {
            return false;
        }
    }
    public function newRegister() {
        if($this->checkIsLoginMail()==true) {
            $token = $this->getToken();
            $this->addNewUser();
            $this->saveToken($token);

            $mailer=Yii::$app->spoolmailer;
            $mailer->newMail('from@domain.com',$this->email,'You were registered','<b>'. Yii::t('app','You have been registered, link to activate You account is down below:').'</b><br/><b><a href="http://praktyki.gda.dev/account/activate?key='.$token.'">Link</a></b>');
        }
        else {
            $this->email='';
            $this->addNewUser();
        }
    }

    public function validateEmail()
    {
        $user = User::findOne(['email'=>$this->email]);
        if ($user!=null) {
            $this->addError('email', 'E-mail znajduje się już w bazie');
        }
    }
    private function checkIsLoginMail(){
        $validator = new EmailValidator();
        if($validator->validate($this->email)==true) {
            return true;
        }
        else {
            return false;
        }
    }


    public function addNewUser() {
        $this->pass = PasswordHelper::hashPassword($this->pass);
        $this->pass_repeat = PasswordHelper::hashPassword($this->pass_repeat);
        $this->login = $this->email;
        $this->user_role =0;
        if($this->validate()){
            $this->save();
            return true;
        }
        else {
            return false;
        }
    }





}
