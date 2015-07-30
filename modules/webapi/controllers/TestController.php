<?php
namespace app\modules\webapi\controllers;

use yii\rest\ActiveController;

class TestController extends ActiveController {

    public $modelClass = 'app\models\Restaurant';

    public function actionGet() {
        return "heh";
    }

}