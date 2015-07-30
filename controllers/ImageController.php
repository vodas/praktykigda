<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\form\Image;

class ImageController extends Controller{

    public function actionJcrop(){
        $model = new Image();

        if ($model->load(Yii::$app->request->post()) ){
            $model->savePicture();
        }
        else{
            return $this->render('crop', ['model' => $model]);
        }

    }

}