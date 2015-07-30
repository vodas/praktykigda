<?php

namespace app\form;

use Yii;
use yii\base\Model;
use app\validators\EmailValidator2;
use app\models\User;

class PasswordReminderForm extends Model
{

    public $email;
    public $verifyCode;

    public function rules()
    {
        return [
            ['email', 'required'],

            ['verifyCode', 'captcha'],

            ['email', EmailValidator2::className()]
        ];
    }

    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    public function sendMessage($token) {

        if ($this->validate()) {
            $mailer=Yii::$app->spoolmailer;
            $mailer->newMail('from@domain.com',$this->email,'Password remind','<b><a href="http://praktyki.gda.dev/login/changepassword?key='.$token.'">Link</a></b>');
            return true;
        } else {
            return false;
        }
    }

    public function dbSaveToken($token) {
        $user = User::find()
            ->where(['email' => $this->email])
            ->one();
        $user->token=$token;
        $user->save();
        return true;
        }
}


