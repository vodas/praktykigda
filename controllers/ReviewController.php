<?php

namespace app\controllers;

use Yii;
use app\models\Review;
use app\models\ReviewCRUD;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReviewController implements the CRUD actions for Review model.
 */
class ReviewController extends Controller
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
     * Lists all Review models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3&&Yii::$app->user->identity->user_role!=2)) {
            return $this->goHome();
        }

        $searchModel = new ReviewCRUD();
        $searchModel->scenario = Review::SCENARIO_READ;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Review model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3&&Yii::$app->user->identity->user_role!=2)) {
            return $this->goHome();
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Review model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3&&Yii::$app->user->identity->user_role!=2)) {
            return $this->goHome();
        }
        $model = new Review(['scenario' => Review::SCENARIO_CREATE]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->review_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Review model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3&&Yii::$app->user->identity->user_role!=2)) {
            return $this->goHome();
        }
        $model = new Review();
        $model->scenario = Review::SCENARIO_UPDATE;
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->review_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Review model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3&&Yii::$app->user->identity->user_role!=2)) {
            return $this->goHome();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Review model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Review the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = new Review();
        $model->scenario = Review::SCENARIO_READ;
        if (($model = Review::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
