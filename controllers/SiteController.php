<?php

namespace app\controllers;

use app\models\Product;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Restaurant;
use app\models\User;
use yii\db\Query;
use app\models\Charts;
use app\charts\LabChartsBar;
use app\charts\LabChartsLine;
use app\charts\LabChartsPie;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionTest($action) {
        return isset($action)? $action : 'hello';
        return json_decode(['data' => 'hello'], JSON_PRETTY_PRINT);
    }
    public function actionSearchProducts(){
        $modelForm = new Product();
        $results = null;
        if ($modelForm->load(Yii::$app->request->post())) {
            $results = $modelForm->giveAllProductsByName($modelForm->name);
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->render('searchProducts',
                [
                    'modelForm' => $modelForm,
                    'results' => $results
                ]);
        }
        else {
            return $this->render('searchProducts',
                [
                    'modelForm' => $modelForm,
                    'results' => $results
                ]);
        }
    }
    public function actionIndex()
    {
        $modelform=new Restaurant();
        $results=new Restaurant();
        if ($modelform->load(Yii::$app->request->post())) {

            $connection = \Yii::$app->db;
            $results = $connection->createCommand("SELECT * FROM restaurants WHERE city LIKE '".$modelform->city."%' AND ((street LIKE '".$modelform->street."%') OR (name LIKE '".$modelform->street."%'))")->queryAll();



          //  $results = Restaurant::find()
           //     ->where(['city' => $model->city, 'street' =>$model->street ])
          //      ->all();



            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->render('index',['modelform' => $modelform,'results'=>$results,]);
        } else {
            return $this->render('index',['modelform' => $modelform,'results'=>$results,]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionChart()
    {
        $customers = User::find()
            ->select(['city','COUNT(*) AS street'])
            ->groupBy('city')
            ->all();

        $restaurants = Restaurant::find()
            ->select(['city','COUNT(*) AS street'])
            ->groupBy('city')
            ->all();

       // $restaurantsOrders = Restaurant::find()
        //    ->select(['{{restaurants}}.name', 'COUNT({{orders}}.order_id) AS restaurant_id'])
        //    ->joinWith('order')
        //    ->groupBy('{{restaurants}}.name')
         //   ->all();
        $connection = \Yii::$app->db;
        $restaurantsOrders = $connection->createCommand('SELECT restaurants.name, Count(orders.order_id) FROM restaurants LEFT JOIN orders ON restaurants.restaurant_id = orders.restaurant_id GROUP BY restaurants.name')->queryAll();


        $chart=new Charts();
        $LabChartsPie = new LabChartsPie();
        $LabChartsPie->setData($chart->getAmountArray($customers));
        $LabChartsPie->setTitle('');
        $LabChartsPie->setSize('400x200');
        $LabChartsPie->setColors('D9351C');
        $LabChartsPie->setLabels($chart->getCities($customers));

        $LabChartsBar = new LabChartsBar();
        $LabChartsBar->setData($chart->getAmountArray($restaurants));
        $LabChartsBar->setSize('400x200');
        $LabChartsBar->setTitle('');
//$LabChartsBar->setColors('D9351C|FAAC02|79D81C|2A9DF0|02AA9D');
        $LabChartsBar->setLabels($chart->getCities($restaurants));
        $LabChartsBar->setBarStyles(40);
        $LabChartsBar->setAxis(10);
        $LabChartsBar->setGrids(10);

        $LabChartsPie2 = new LabChartsPie();
        $LabChartsPie2->setData($chart->getAmountOrderArray($restaurantsOrders));
//$LabChartsPie->setType('p3');
        $LabChartsPie2->setTitle('');
        $LabChartsPie2->setSize('400x200');
        $LabChartsPie2->setColors('D9351C');
        $LabChartsPie2->setLabels($chart->getRestaurantNames($restaurantsOrders));


        return $this->render('chart',[
            'LabChartsPie' => $LabChartsPie,
            'LabChartsPie2' => $LabChartsPie2,
            'LabChartsBar' => $LabChartsBar,
        ]);
    }
}
