<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserCRUD;
use app\components\PasswordHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\ErrorHelper;


class UserController extends Controller
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


    public function actionIndex()
    {
        if(Yii::$app->user->isGuest||Yii::$app->user->identity->user_role!=4) {
            return $this->goHome();
        }
        $searchModel = new UserCRUD();
        $searchModel->scenario = User::SCENARIO_READ;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(Yii::$app->user->isGuest||Yii::$app->user->identity->user_role!=4) {
            return $this->goHome();
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    private function createAjax() {
        $data = Yii::$app->request->post();
        $model = new User();
        $model->scenario = User::SCENARIO_CREATE;

        foreach ($data['User'] as $key => $value) {
            $model->$key = $value;
        }
        if ($model->validate()) {
            $model->pass = PasswordHelper::hashPassword($model->pass);
            $model->pass_repeat = PasswordHelper::hashPassword($model->pass_repeat);
            return $model->save() ? true : ErrorHelper::errorsToString($model->errors);
        } else {
            return ErrorHelper::errorsToString($model->errors);
        }
    }
    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->isGuest || Yii::$app->user->identity->user_role!=4) {
            return $this->goHome();
        } else {
            if(Yii::$app->request->isAjax) {
                return $this->createAjax();
            } else {
                $model = new User();
                $model->scenario = User::SCENARIO_CREATE;
                return $this->render('create', [
                    'model' => $model,
                ]);

            }
        }
    }

    private function updateAjax() {
        $data = Yii::$app->request->post();
        $model = User::findOne(['user_id' => $_GET['id']]);
        if($model != null) {
            $model->scenario = User::SCENARIO_UPDATE;
            foreach ($data['User'] as $key => $value) {
                $model->$key = $value;
            }
            if ($model->validate()) {
                $model = PasswordHelper::checkPassword($model);
                $model->save();
                return true;
            } else {
                return ErrorHelper::errorsToString($model->errors);
            }
        }
    }
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->isGuest || Yii::$app->user->identity->user_role!=4) {
            return $this->goHome();
        } else {
            if(Yii::$app->request->isAjax) {
                return $this->updateAjax();
            } else {
                $model = $this->findModel($id);
                $model->scenario = User::SCENARIO_UPDATE;
                return $this->render('update', [
                    'model' => $model,
                ]);

            }
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->isGuest||Yii::$app->user->identity->user_role!=4) {
            return $this->goHome();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = new User();
        $model->scenario = User::SCENARIO_READ;
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
