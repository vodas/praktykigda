<?php
namespace app\modules\webapi\controllers;

use app\modules\webapi\Codes;
use Yii;
use app\modules\webapi\StatusCodeMessage;
use yii\web\Controller;

class ApiController extends Controller {
    public function beforeAction($event)
    {
        $action = $event->id;
        if (isset($this->actions[$action])) {
            $verbs = $this->actions[$action];
        } elseif (isset($this->actions['*'])) {
            $verbs = $this->actions['*'];
        } else {
            return true;
        }
        $verb = Yii::$app->getRequest()->getMethod();

        $allowed = array_map('strtoupper', $verbs);

        if (!in_array($verb, $allowed)) {

            echo json_encode(array('status_code'=>CODES::$BAD_REQUEST,'message'=>StatusCodeMessage::$BAD_REQUEST),JSON_PRETTY_PRINT);
            exit;
        }
        return true;
    }

}