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
 * webapi/register
 *
 * you must send login, pass and email
*/

class RegisterController extends Controller
{


    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $params = $_REQUEST;

        $model = new User();
        $model->attributes = $params;

        if (!isset($model->login) || !isset($model->pass) || !isset($model->email)) {
            echo json_encode(array('status' => 0, 'error_code' => Codes::$REQUIRED, 'errors' => StatusCodeMessage::$REQUIRED ), JSON_PRETTY_PRINT);
        } else {

            $userByLogin = User::find()->where(['login' => $model->login])->one();
            $userByEmail = User::find()->where(['email' => $model->email])->one();

            if (isset($userByLogin) && isset($userByEmail)) {
                echo json_encode(array('status' => 0, 'error_code' => Codes::$ALREADY, 'errors' => StatusCodeMessage::$ALREADY ), JSON_PRETTY_PRINT);
            } else {

                $model->pass=hash('sha256',Yii::$app->params['hashSalt'].$model->pass);
                $model->user_role=0;

                if ($model->save()) {
                    echo json_encode(array('status' => 1, 'code'=> 200, 'data' => 'Account added to database'), JSON_PRETTY_PRINT);
                } else {
                    echo json_encode(array('status' => 0, 'error_code' => Codes::$BAD_REQUEST, 'errors' => $model->errors), JSON_PRETTY_PRINT);
                }
            }
        }
    }

}