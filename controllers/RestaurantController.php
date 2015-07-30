<?php

namespace app\controllers;


use app\models\FileDownload;
use app\models\FileUpload;
use app\models\Product;
use app\models\Warehouse;
use Yii;
use app\models\Restaurant;
use app\models\RestaurantCRUD;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use mPDF;
use yii\web\UploadedFile;

/**
 * RestaurantController implements the CRUD actions for Restaurant model.
 */
class RestaurantController extends Controller
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
     * Lists all Restaurant models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3)) {
            return $this->goHome();
        }


        $searchModel = new RestaurantCRUD();
        $searchModel->scenario = Restaurant::SCENARIO_READ;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReset(){
        return $this->actionIndex();
    }
    /**
     * Displays a single Restaurant model.
     * @param string $id
     * @return mixed
     */
    public function actionMenuExportToCsv($id){
        $var = new FileDownload();
        $var->exportRestaurantMenuToCsv($id);
        $this->goHome();
    }

    public function actionView($id)
    {

        $model = $this->findModel($id);
        $menu = new FileUpload();
        $model->scenario = Restaurant::SCENARIO_READ;

        if($menu->load(Yii::$app->request->post()) ){
            $this->addMenu($menu, $id);
        }

        return $this->render('view', [
            'model' => $model,
            'menu' => $menu
        ]);
    }

    /**
     * Creates a new Restaurant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3)) {
            return $this->goHome();
        }


        $model = new Restaurant(['scenario' => Restaurant::SCENARIO_CREATE]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->restaurant_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function addMenu($menu, $id){
        $menu->uploadMenuJson($id);
        return $this->goHome();
    }
    /**
     * Updates an existing Restaurant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3)) {
            return $this->goHome();
        }


        $model = $this->findModel($id);
        $model->scenario = Restaurant::SCENARIO_UPDATE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->restaurant_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Restaurant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3)) {
            return $this->goHome();
        }


        $model = $this->findModel($id);
        $model->scenario = Restaurant::SCENARIO_DELETE;
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Restaurant::findOne($id)) !== null) {
            $model->scenario = Restaurant::SCENARIO_READ;
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return string
     */
    public function actionCreatepdf() {

        if(Yii::$app->user->isGuest||(Yii::$app->user->identity->user_role!=4&&Yii::$app->user->identity->user_role!=3)) {
            return $this->goHome();
        }

        $model = new Restaurant();
        if ($model->load(Yii::$app->request->post())) {
            $mpdf = new mPDF;
            $connection = \Yii::$app->db;
            $warehouses = $connection->createCommand('SELECT warehouse.description, warehouse.price, products.name FROM warehouse LEFT JOIN products ON warehouse.product_id=products.product_id WHERE warehouse.restaurant_id='.$model->restaurant_id.'')->queryAll();

            $restaurant = Restaurant::find()
                ->where(['restaurant_id' => $model->restaurant_id ])
                ->one();
            $mpdf->WriteHTML('<h1>Menu restauracji '.$restaurant->name.' '.$restaurant->city.'</h1>');
            foreach ($warehouses as $warehouse) {
                $mpdf->WriteHTML($warehouse['name'].'<br> Opis: '.$warehouse['description'].'<br>Cena: '.$warehouse['price'].'<br><br><br>');
            }
            $mpdf->Output();
            exit;
        }
        return $this->render('createpdf', ['model' => $model]);
    }


}
