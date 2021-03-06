<?php

namespace app\modules\webapi\controllers;
use Yii;
use app\models\Restaurant;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\web\Response;
use app\modules\webapi\Codes;
use app\modules\webapi\StatusCodeMessage;
use yii\web\Request;
use app\modules\webapi\models\UserRoleDetector;


/**
 * Functions:
 * {get}
 * webapi/restaurant
 *view of all restaurants
 *
 * webapi/restaurant/{id}
 *view of one restaurant
 *
 * webapi/restaurant/create
 *
 * creating new restaurant
 *
 *webapi/restaurant/update/[id]
 *
 * update restaurant by id
 *
 *webapi/restaurant/delete/[id]
 *
 * deleting one restaurant by id
 *
 *webapi/restaurant/deleteall
 *
 *deleting all the restaurants
 */



class RestaurantController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'view' => ['get'],
                    'create' => ['post'],
                    'update' => ['post'],
                    'delete' => ['delete'],
                    'deleteall' => ['post'],
                ],

            ]
        ];
    }


    public function beforeAction($event)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $action = $event->id;
        if (isset($this->actions[$action])) {
            $verbs = $this->actions[$action];
        } elseif (isset($this->actions['*'])) {
            $verbs = $this->actions['*'];
        } else {
            return $event->isValid;
        }
        $verb = Yii::$app->getRequest()->getMethod();

        $allowed = array_map('strtoupper', $verbs);

        if (!in_array($verb, $allowed)) {

            echo json_encode(array('status'=>0, 'error_code'=>Codes::$BAD_REQUEST, 'message'=>StatusCodeMessage::$BAD_REQUEST), JSON_PRETTY_PRINT);
            exit;

        }

        return true;
    }


    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $params = Yii::$app->getRequest();
        $filter = array();
        $sort = "";


        $page = isset($params->page) ? $params->page : 1;
        $limit = isset($params->limit) ? $params->limit : 10;


        $offset = $limit*($page-1);


        /* Filter elements */
        if(isset($params->filter))
        {
            $filter = (array)json_decode($params->filter);
        }




        if(isset($params->sort))
        {
            $sort = $params->sort;
            if(isset($params->order))
            {
                if($params->order == "false")
                    $sort.= " desc";
                else
                    $sort.= " asc";

            }
        }


        $query = new Query;
        $query->offset($offset)
            ->limit($limit)
            ->from('restaurants')
            ->orderBy($sort)
            ->select("restaurant_id, name, street, house_nr, flat_nr, zip_code, city");


        $command = $query->createCommand();
        $models = $command->queryAll();

        $totalItems = $query->count();


        echo json_encode(array('status'=>1, 'code'=> 200, 'data'=>$models,'totalItems'=>$totalItems ), JSON_PRETTY_PRINT);

    }


    public function actionView($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        echo json_encode(array('status'=>1, 'data'=>array_filter($model->attributes)), JSON_PRETTY_PRINT);

    }


    public function actionCreate()
    {
        $role=UserRoleDetector::getUserRole();

        Yii::$app->response->format = Response::FORMAT_JSON;
        if($role != 3 && $role != 4) {
            echo json_encode(array('status'=>0, 'error_code'=>Codes::$UNAUTHORIZED, 'errors'=>StatusCodeMessage::$UNAUTHORIZED), JSON_PRETTY_PRINT);
        } else {
            $params = $_REQUEST;
            $model = new Restaurant();
            $model->attributes = $params;

            if ($model->save()) {
                echo json_encode(array('status' => 1, 'code'=> 200, 'data' => array_filter($model->attributes)), JSON_PRETTY_PRINT);
            } else {
                echo json_encode(array('status' => 0, 'error_code' => Codes::$BAD_REQUEST, 'errors' => $model->errors), JSON_PRETTY_PRINT);
            }
        }

    }


    public function actionUpdate($id)
    {
        $role=UserRoleDetector::getUserRole();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($role != 3 && $role != 4) {
            echo json_encode(array('status'=>0, 'error_code'=>Codes::$UNAUTHORIZED, 'errors'=>StatusCodeMessage::$UNAUTHORIZED), JSON_PRETTY_PRINT);
        } else {

            $params = $_REQUEST;

            $model = $this->findModel($id);

            $model->attributes = $params;
            if ($model->save()) {

                echo json_encode(array('status' => 1, 'code'=> 200, 'data' => array_filter($model->attributes)), JSON_PRETTY_PRINT);

            } else {
                echo json_encode(array('status' => 0, 'error_code' => Codes::$BAD_REQUEST, 'errors' => $model->errors), JSON_PRETTY_PRINT);
            }
        }

    }

    public function actionDelete($id)
    {
        $role=UserRoleDetector::getUserRole();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($role != 3 && $role != 4) {
            echo json_encode(array('status'=>0, 'error_code'=>Codes::$UNAUTHORIZED, 'errors'=>StatusCodeMessage::$UNAUTHORIZED), JSON_PRETTY_PRINT);
        } else {
            $model = $this->findModel($id);

            if ($model->delete()) {
                echo json_encode(array('status' => 1, 'code'=> 200, 'data' => array_filter($model->attributes)), JSON_PRETTY_PRINT);

            } else {

                echo json_encode(array('status' => 0, 'error_code' => Codes::$BAD_REQUEST, 'errors' => $model->errors), JSON_PRETTY_PRINT);
            }
        }
    }

    public function actionDeleteall()
    {
        $role=UserRoleDetector::getUserRole();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($role != 3 && $role != 4) {
            echo json_encode(array('status'=>0, 'error_code'=>Codes::$UNAUTHORIZED, 'errors'=>StatusCodeMessage::$UNAUTHORIZED), JSON_PRETTY_PRINT);
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $ids = json_decode($_REQUEST['ids']);
            $stringIds = implode(",", $ids);
            if (Restaurant::deleteAll('warehouse_id IN (' . $stringIds . ')')) {
                echo json_encode(array('status' => 1, 'code'=> 200, 'data' => 'deleted'), JSON_PRETTY_PRINT);
            } else {
                echo json_encode(array('status' => 0, 'error_code' => Codes::$BAD_REQUEST,), JSON_PRETTY_PRINT);
            }
        }
    }

    protected function findModel($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (($model = Restaurant::find()->where(['restaurant_id' => $id])->one()) !== null) {
            return $model;
        } else {

            echo json_encode(array('status'=>0, 'error_code'=>Codes::$BAD_REQUEST, 'message'=>StatusCodeMessage::$BAD_REQUEST), JSON_PRETTY_PRINT);
            exit;
            // throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



}