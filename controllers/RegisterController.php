<?php

namespace app\controllers;

use app\form\Image;
use app\models\User;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use app\form\RegisterForm;

class RegisterController extends Controller
{
    public function actions() {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

    public function actionRegister()
    {
        if(Yii::$app->user->isGuest == false) {
            return $this->goHome();
        } else {
            $model = new RegisterForm(['scenario' => User::SCENARIO_CREATE]);

            if ($model->load(Yii::$app->request->post())) {
                $model->newRegister();

                Yii::$app->getSession()->setFlash('mailSended', Yii::t('app', 'We sended You link to activate Your account'));
                return $this->redirect(Url::to('@web/login/login'));
            } else {
                return $this->render('register', [
                    'model' => $model,
                ]);
            }
        }

    }

}