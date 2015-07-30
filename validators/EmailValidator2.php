<?php

namespace app\validators;

use yii\validators\Validator;
use app\models\User;

class EmailValidator2 extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = 'Nie ma takiego maila w bazie !';
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (!User::find()->where(['email' => $value])->exists()) {
            $model->addError($attribute, $this->message);
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $statuses = json_encode(User::find()->select('email')->asArray()->column());
        $message = json_encode($this->message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return <<<JS
if ($.inArray(value, $statuses)==-1) {
    messages.push($message);
}
JS;
    }
}