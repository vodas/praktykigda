<?php

namespace app\validators;

use yii\validators\Validator;
use app\models\User;

class LoginValidator extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = 'Login jest już w użyciu';
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (User::find()->where(['login' => $value])->exists()) {
            $model->addError($attribute, $this->message);
        }
    }

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @param \yii\web\View $view
     * @return string
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $statuses = json_encode(User::find()->select('login')->asArray()->column());
        $message = json_encode($this->message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return <<<JS
        if ($.inArray(value, $statuses)>-1) {
            messages.push($message);
        }
JS;
    }
}