<?php

namespace app\validators;

use yii\validators\Validator;
use app\models\User;

class EmailUpdateValidator extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = 'E-mail jest już w użyciu';
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;

        $user = User::find()
            ->where(['login' => $model->login])
            ->one();
        $email=$user->email;

        if (User::find()->where(['email' => $value])->exists()&&$email!=$value) {
            $model->addError($attribute, $this->message);
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $user = User::find()
            ->where(['login' => $model->login])
            ->one();
        $email=$user->email;



        $statuses = json_encode(User::find()->select('email')->asArray()->column());

        $message = json_encode($this->message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return <<<JS
    $statuses.splice( $.inArray($email,$statuses) ,1 );
if ($.inArray(value, $statuses)>-1) {
    messages.push($message);
}
JS;
    }
}