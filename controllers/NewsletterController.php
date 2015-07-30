<?php

namespace app\controllers;

use Yii;
use app\models\Newsletter;
use app\models\NewsletterCRUD;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * NewsletterController implements the CRUD actions for Newsletter model.
 */
class NewsletterController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Newsletter models.
     * @return mixed
     */
    public function actionIndex()
    {

        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3)) {
            return $this->goHome();
        }


        $searchModel = new NewsletterCRUD();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Newsletter model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3)) {
            return $this->goHome();
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Newsletter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3)) {
            return $this->goHome();
        }
        $model = new Newsletter();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->newsletter_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function actionUpdate($id)
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3&&Yii::$app->user->identity->user_role!=2)) {
            return $this->goHome();
        }
        $model = new Newsletter();
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->newsletter_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    public function actionSend($id)
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3)) {
            return $this->goHome();
        }
        $model = Newsletter::find()
            ->where(['newsletter_id' => $id])
            ->one();
        $model->sent=true;
        $model->update();
        $users = User::find()
            ->where(['newsletter' => 1])
            ->all();

        $mailer=Yii::$app->spoolmailer;
        foreach ($users as $user) {
            $mailer->newMail('from@domain.com',$user->email,'Newsletter ze strony',$model->news);
        }
        $mailer->send();


      return $this->redirect(['index']);
    }








    protected function findModel($id)
    {
        if (($model = Newsletter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
