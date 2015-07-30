<?php

namespace app\modules\webapi\controllers;
use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\web\Response;
use app\modules\webapi\Codes;
use app\modules\webapi\StatusCodeMessage;

/*
 * webapi/login
 *
 * you must send login and pass
 *
 * in response you will get apitoken
 *
*/


class LoginController extends Controller {

    public function actionIndex() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $params = $_REQUEST;
        $model = new User();
        if(isset($params['login']) && isset($params['pass'])) {
            $model->login = $params['login'];
            $model->pass = hash('sha256', Yii::$app->params['hashSalt'] . $params['pass']);
            $user = User::find()->where("login='" . $params['login'] . "' OR email='" . $params['login'] . "'")->one();

            if ($user->pass == $model->pass) {
                //$headers=Yii::$app->getRequest()->getHeaders();
                $user->apitime=time();
                $user->apitoken=md5(rand(100000000000000,500000000000000));
                $user->update();
                header('apitoken: '.$user->apitoken);
                echo json_encode(array('status' => 1, 'error_code' => Codes::$LOGGED, 'message'=>StatusCodeMessage::$LOGGED), JSON_PRETTY_PRINT);
            } else {
                echo json_encode(array('status' => 0, 'error_code' => Codes::$UNAUTHORIZED, 'message'=>StatusCodeMessage::$UNAUTHORIZED), JSON_PRETTY_PRINT);
            }

        } else {
            echo json_encode(array('status' => 0, 'error_code' => Codes::$BAD_REQUEST, 'message'=>StatusCodeMessage::$BAD_REQUEST), JSON_PRETTY_PRINT);
        }

    }
}