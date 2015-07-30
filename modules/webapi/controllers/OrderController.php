<?php
namespace app\modules\webapi\controllers;

use app\models\Order;
use app\models\Restaurant;
use app\models\User;
use Yii;
use app\modules\webapi\controllers\ApiController;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\db\Query;
use app\modules\webapi\models\OrderApi;
use app\modules\webapi\models\UserRoleDetector;
use yii\web\Response;
use app\modules\webapi\Codes;
use app\modules\webapi\StatusCodeMessage;


class OrderController extends ApiController {
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'readAll'=>['get'],
                    'readSpecific'=>['get'],
                    'create'=>['post'],
                    'update'=>['post'],
                    'delete' => ['post']
                ],
            ]
        ];
    }

    /**
     * Updating order
     * User have to give order_id to program know which record is to update
     * Other attributes is optional and will update it value
     */
    public function actionUpdate()
    {
        $role=UserRoleDetector::getUserRole();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($role != 3 && $role != 4) {
            echo json_encode(array('error_code' => Codes::$UNAUTHORIZED, 'errors' => StatusCodeMessage::$UNAUTHORIZED), JSON_PRETTY_PRINT);
        } else {
            $params = $_REQUEST;
            if (!isset($params['order_id'])) {
                $this->setHeader(CODES::$BAD_REQUEST);
                echo json_encode(array('status_code' => CODES::$BAD_REQUEST, 'message' => StatusCodeMessage::$BAD_REQUEST), JSON_PRETTY_PRINT);
                exit;
            } else {
                if (($order = Order::findOne(['order_id' => $params['order_id']])) != null) {
                    if (isset($params['user_id'])) {
                        $order->user_id = $params['user_id'];
                    }
                    if (isset($params['user_info'])) {
                        $order->user_info = $params['user_info'];
                    }
                    if (isset($params['restaurant_id'])) {
                        $order->restaurant_id = $params['restaurant_id'];
                    }
                    if (isset($params['order_info'])) {
                        $order->order_info = $params['order_info'];
                    }
                    if (isset($params['status'])) {
                        $order->status = $params['status'];
                    }
                    $order->save();
                    echo json_encode(array('status_code' => CODES::$OK, 'message' => StatusCodeMessage::$OK), JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(array('status_code' => CODES::$NOT_FOUND, 'message' => StatusCodeMessage::$NOT_FOUND), JSON_PRETTY_PRINT);
                    exit;
                }
            }
        }
    }

    /**
     * Creating new order
     * User have to give restaurant_id and it should exist in table restaurants
     * Other attributes is optional, gives status value of Order::STATUS_CREATED
     *
     * @throws \yii\db\Exception
     */
    public function actionCreate() {

        $role=UserRoleDetector::getUserRole();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($role != 3 && $role != 4) {
            echo json_encode(array('error_code' => Codes::$UNAUTHORIZED, 'errors' => StatusCodeMessage::$UNAUTHORIZED), JSON_PRETTY_PRINT);
        } else {
            $params = $_REQUEST;

            //checking restaurant_id
            if(!isset($params['restaurant_id'])) {
                echo json_encode(array('status_code' => CODES::$BAD_REQUEST, 'message' => StatusCodeMessage::$BAD_REQUEST),JSON_PRETTY_PRINT);
                exit;
            } else {
                $restaurant = Restaurant::findOne(['restaurant_id' => $params['restaurant_id']]);
                if($restaurant == null) {
                    echo json_encode(array('status_code' => CODES::$NOT_FOUND,'message'=>StatusCodeMessage::$NOT_FOUND),JSON_PRETTY_PRINT);
                    exit;
                }
            }

            $query = new Query();
            $result = $query
                ->createCommand()
                ->insert('orders', [
                    //if user_id does not exist gives null, else gives user_id
                    "user_id" => isset($params['user_id']) ? (User::findOne(['user_id' => $params['user_id']]) != null ? $params['user_id'] : null) : null,
                    "user_info" => isset($params['user_info']) ? $params['user_info'] : null,
                    "restaurant_id" => $params['restaurant_id'],//if case of no exist gives an error
                    "order_info" => isset($params['order_info']) ? $params['order_info'] : '',
                    "status" => Order::STATUS_CREATED,
                    "other_users" => ''
                ])
                ->execute();
            if($result == null) {
                echo json_encode(array('status_code' => Codes::$INERNAL, 'message' => StatusCodeMessage::$INERNAL),JSON_PRETTY_PRINT);
                exit;
            } else {
                $model = $query
                    ->from('orders')
                    ->select(['order_id', 'user_id', 'user_info', 'restaurant_id', 'order_info'])
                    ->where(['order_id' => $query->max('order_id')])
                    ->createCommand()
                    ->queryAll();
                echo json_encode(array('status_code' => Codes::$CREATED,'message' => StatusCodeMessage::$CREATED, 'data' => $model), JSON_PRETTY_PRINT);
            }
        }
    }

    /**
     * Read all records and return on echo
     * View search documentation to know which attributes You can give
     */
    public function actionReadAll()
    {
        $role=UserRoleDetector::getUserRole();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($role != 3 && $role != 4) {
            echo json_encode(array('error_code' => Codes::$UNAUTHORIZED, 'errors' => StatusCodeMessage::$UNAUTHORIZED), JSON_PRETTY_PRINT);
        } else {

            $params = $_REQUEST;

            $model = new OrderApi();
            $result = $this->search($params);

            $result = $model->serializeToJson($result);

            echo json_encode(array('status_code' => Codes::$OK, 'message' => StatusCodeMessage::$OK, 'data' => $result) ,JSON_PRETTY_PRINT);
        }
    }

    /**
     * Search which user can give attributes like
     * sort to sort
     * page to write page You want to see, connected with limit default = 1
     * limit to limit records on one page, default = 10
     *
     * and attributes that is name of column in table
     *
     * @param $param
     * @return array
     */
    private function search($param) {
        $model = new OrderApi();
        $where = null;
        $sort = "";

        $page = 1;
        $limit = 10;

        if (isset($params['page'])) {
            $page = $params['page'];
        }

        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }

        $offset = $limit * ($page - 1);

        if (isset($params['sort'])) {
            $sort = $params['sort'];
            if (isset($params['order'])) {
                if ($params['order'] == "false")
                    $sort .= " desc";
                else
                    $sort .= " asc";
            }
        }

        if(isset($param['order_id'])) {
            $where['order_id'] = $param['order_id'];
        }
        if(isset($param['user_id'])) {
            $where['user_id'] = $param['user_id'];
        }
        if(isset($param['restaurant_id'])) {
            $where['restaurant_id'] = $param['restaurant_id'];
        }
        if(isset($param['order_info'])) {
            $where['order_info'] = $param['order_info'];
        }
        if(isset($param['order_info'])) {
            $where['order_info'] = $param['order_info'];
        }
        if(isset($param['status'])) {
            $where['status'] = $param['status'];
        }

        $query = new Query();
        $result = $query
            ->from('orders')
            ->select(['order_id', 'status', 'user_id', 'user_info', 'restaurant_id', 'order_info'])
            ->orderBy($sort)
            ->offset($offset)
            ->where($where)
            ->limit($limit)
            ->createCommand()
            ->queryAll();
        $result = $model->serializeToJson($result);

        return $result;
    }

    /**
     * Read specific record
     * User can find by every attribute that is name of column
     *
     */
    public function actionReadSpecific($id)
    {
        $role=UserRoleDetector::getUserRole();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($role != 3 && $role != 4) {
            echo json_encode(array('status' => 0, 'error_code' => Codes::$UNAUTHORIZED, 'errors' => StatusCodeMessage::$UNAUTHORIZED), JSON_PRETTY_PRINT);
        } else {
            $query = new Query();
            $model = new OrderApi();
            $result = $query
                ->from('orders')
                ->select(['order_id', 'status', 'user_id', 'user_info', 'restaurant_id', 'order_info'])
                ->where(['order_id' => $id])
                ->createCommand()
                ->queryAll();
            $result = $model->serializeToJson($result);
            if ($result == null) {
                echo json_encode(array('status_code' => CODES::$NOT_FOUND, 'message' => StatusCodeMessage::$NOT_FOUND), JSON_PRETTY_PRINT);
                exit;
            } else {
                echo json_encode(array('status_code' => Codes::$OK, 'message' => StatusCodeMessage::$OK, 'data' => $result), JSON_PRETTY_PRINT);
            }
        }
    }

    /**
     * Deleting all records that succeded where condition
     *
     * @throws \yii\db\Exception
     */
    public function actionDelete() {
        $role=UserRoleDetector::getUserRole();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($role != 3 && $role != 4) {
            echo json_encode(array('error_code' => Codes::$UNAUTHORIZED, 'errors' => StatusCodeMessage::$UNAUTHORIZED), JSON_PRETTY_PRINT);
        } else {
            $query = new Query();
            $model = new OrderApi();
            $where = null;

            //result to contain all records to delete
            if(isset($_REQUEST['order_id'])) {
                $where['order_id'] = $_REQUEST['order_id'];
            }
            if(isset($_REQUEST['user_id'])) {
                $where['user_id'] = $_REQUEST['user_id'];
            }
            if(isset($_REQUEST['restaurant_id'])) {
                $where['restaurant_id'] = $_REQUEST['restaurant_id'];
            }
            if(isset($_REQUEST['status'])) {
                $where['status'] = $_REQUEST['status'];
            }

            $result =  $query
                ->from('orders')
                ->select(['order_id', 'status', 'user_id', 'user_info', 'restaurant_id', 'order_info'])
                ->where($where)
                ->createCommand()
                ->queryAll();
            //serialize that data that should be in json format
            $result = $model->serializeToJson($result);

            if($result != null) {
                //show all records that will be deleted
                echo json_encode(array('status_code' => Codes::$OK,'message' => StatusCodeMessage::$OK, 'data' => $result), JSON_PRETTY_PRINT);
                //delete that records
                $delete = $query
                    ->createCommand()
                    ->delete('orders', $where)
                    ->execute();

                if($delete == true) {
                    echo json_encode(array('status_code' => Codes::$OK, 'message' => StatusCodeMessage::$OK), JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(array('status_code' => Codes::$INERNAL, 'message' => StatusCodeMessage::$INERNAL), JSON_PRETTY_PRINT);
                    exit;
                }
            }
            else {
                echo json_encode(array('status_code' => CODES::$NOT_FOUND, 'message' => StatusCodeMessage::$NOT_FOUND),JSON_PRETTY_PRINT);
            }
        }
    }

    /* Functions to set header with status code. eg: 200 OK ,400 Bad Request etc..*/
    private function setHeader($status)
    {

        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        $content_type="application/json; charset=utf-8";

        header($status_header);
        header('Content-type: ' . $content_type);
        header('X-Powered-By: ' . "Nintriva <nintriva.com>");
    }
    private function _getStatusCodeMessage($status)
    {
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

}