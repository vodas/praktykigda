<?php

namespace app\controllers;

use app\form\ChangePasswordForm;
use app\form\LoginForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use app\form\ChangeData;
use app\form\Image;
use Yii;
use yii\web\Controller;
use app\models\user;

//Controller for user account manage
class AccountController extends Controller{
    //changing password for logged user
    public function actionChangePassword() {
        if(Yii::$app->user->isGuest == false) {
            $model = new ChangePasswordForm();
            if ($model->load(Yii::$app->request->post()) && $model->loggedChangePassword(Yii::$app->user->identity->username)) {
                Yii::$app->session->setFlash('contactFormSubmitted');
                return $this->refresh();
            } else if($model->load(Yii::$app->request->post()) && !$model->loggedChangePassword(Yii::$app->user->identity->username)) {
                return $this->goHome();
            } else {
                return $this->render('//login/changepassword', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->goHome();
        }
    }
    //delete account
    public function actionDeleteAccount() {
        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post())) {
            $user = User::findOne(['login' => Yii::$app->user->identity->username]);
            if($user != null && $user->pass == hash('sha256', Yii::$app->params['hashSalt'] . $model->password)) {
                $user->delete();
                Yii::$app->getSession()->setFlash('deleted', Yii::t('app', 'You account is deleted'));
                Yii::$app->user->logout();
                $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('activated', Yii::t('app', 'Bad password'));
                $this->goHome();
            }
        } else if(!Yii::$app->user->isGuest){
            return $this->render('deleteAccount',
                [
                    'model' => $model
                ]);
        } else {
            return $this->goHome();
        }
    }
    public function actionActivate($key){
        $user = User::findOne(['token' => $key]);
        if($user == null) {
            return $this->goHome();
        } else {
            Yii::$app->getSession()->setFlash('activated', Yii::t('app', 'You account is now activated'));
            $user->token = '';
            $user->save();
            $form = new LoginForm();
            return $this->redirect(\Yii::$app->urlManager->createUrl("login/login"));
        }
    }
    public function actionChangeImageAjax() {
        //$image = new Image();
        $image = UploadedFile::getInstanceByName($_FILES['User']);
        return $image;
    }

    private function actionChangeDataAjax() {
        Yii::$app->params['uploadPath'] = Yii::$app->basePath. '/web/upload/';
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model = $this->findUser();
            $imageJcrop = new Image();

            foreach ($data['User'] as $key => $value) {
                $model->$key = $value;
            }
            foreach ($data['Image'] as $key => $value) {
                $imageJcrop->$key = $value;
            }
            $model->image = isset($_FILES['image']) ? $_FILES['image'] : null;
            $imageHandler = UploadedFile::getInstance($model, 'image');
            if ($model->validate()) {
                if ($imageHandler != null) {
                    if (file_exists(Yii::$app->params['uploadPath'] . $model->filename) && $model->filename != null) {
                        unlink(Yii::$app->params['uploadPath'] . $model->filename);
                    }
                    $model = $this->savePic($model, $imageHandler);
                }
                if ($imageJcrop->x != '') {
                    $this->resizePic($model, $imageJcrop);
                }

                $model = $this->checkPassword($model);
                $model->save();
                return true;
            } else {
                $string = "";
                foreach ($model->errors as $error) {
                    foreach ($error as $key => $value) {
                        $string .= $value . "\n";
                    }
                }
                return $string;
            }
        }
//        if ($model->load(Yii::$app->request->post()) && $this->checkPassword($model)->save() && $imageHandler->load(Yii::$app->request->post())) {
//                $image = UploadedFile::getInstance($model, 'image');
//                if($image != null){
//                    if(file_exists(Yii::$app->params['uploadPath'].$model->filename) && $model->filename!=null){
//                        unlink(Yii::$app->params['uploadPath'].$model->filename);
//                    }
//                    $this->savePic($model, $image);
//                }
//                if($imageHandler->x != '') {
//                    $this->resizePic($model, $imageHandler);
//                }
//
//                //Go to image formatting(to do)
//                Yii::$app->session->setFlash('dataChanged');
//                return $this->refresh();
//        } else {
//        }
    }
    public function actionChangeData() {
        if(!Yii::$app->user->isGuest && !Yii::$app->request->isAjax) {
            Yii::$app->params['uploadPath'] = Yii::$app->basePath. '/web/upload/';
            $model = $this->findUser();
            $imageHandler = new Image();

            return $this->render('changeData', [
                'model' => $model,
                'image' => $imageHandler
            ]);
        } else {
            return $this->actionChangeDataAjax();
        }

    }
    public function checkPassword($modelCheck){
        $model = $this->findUser(['login' => $modelCheck->login]);
        if($modelCheck->pass=='' && $model!=null && $model->pass!=''){
            $modelCheck->pass = $model->pass;
        }
        elseif($modelCheck->pass != '') {
            $modelCheck->pass = hash('sha256',Yii::$app->params['hashSalt'].$modelCheck->pass);
        }
        return $modelCheck;
    }

    private function createImageByType($filename){
        $type = exif_imagetype($filename);
        if($type == IMAGETYPE_JPEG) {
            $img_r = imagecreatefromjpeg($filename);
        }
        else if($type == IMAGETYPE_PNG) {
            $img_r = imagecreatefrompng($filename);
        }
        else if($type == IMAGETYPE_GIF) {
            $img_r = imagecreatefromgif($filename);
        }
        else{
            $img_r = null;
        }
        return $img_r;
    }
    private function resizePic($model, $imageHandler){
        $src = Yii::$app->params['uploadPath'].$model->filename;
        $jpeg_quality = 90;
        //resize pic to size which user saw
        $src = Yii::$app->params['uploadPath'].$model->filename;
        $img_r = $this->createImageByType($src);
        $dst_r = ImageCreateTrueColor(500, 370);
        list($width, $height) = getimagesize($src);

        imagecopyresampled($dst_r,$img_r, 0, 0, 0, 0,
            500, 370, $width, $height);

        header('Content-type: image/jpeg');
        imagejpeg($dst_r, $src, $jpeg_quality);
        //end of resizing
        //second resizing
        $targ_w = $targ_h = 200;

        $img_r = $this->createImageByType($src);
        $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

        imagecopyresampled($dst_r,$img_r,0,0,$imageHandler->x,$imageHandler->y,
            $targ_w,$targ_h,$imageHandler->w,$imageHandler->h);

        header('Content-type: image/jpeg');
        imagejpeg($dst_r, $src, $jpeg_quality);
    }
    private function savePic($model, $image){
        $ext = end((explode(".", $image->name)));

        // generate a unique file name
        $model->filename = Yii::$app->security->generateRandomString().".{$ext}";

        // the path to save file, you can set an uploadPath
        // in Yii::$app->params (as used in example below)
        $path = Yii::$app->params['uploadPath'] . $model->filename;
        $image->saveAs($path);
        return $model;
    }
    private function findUser(){
        $search = new User();
        if(($token = Yii::$app->request->get('key'))!== null){
            $model = $search->findOne(['token' => $token]);
        }
        else if(Yii::$app->user->isGuest == false){
            $model = $search->findOne(['login' => Yii::$app->user->identity->username]);
        }
        else{
            return null;
        }
        return $model;
    }
}