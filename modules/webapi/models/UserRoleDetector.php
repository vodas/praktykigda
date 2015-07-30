<?php

namespace app\modules\webapi\models;

use Yii;
use app\models\User;

class UserRoleDetector
{

    public static function getUserRole()
    {
        $headers = Yii::$app->getRequest()->getHeaders();

        if (isset($headers['apitoken'])) {
            $user = User::find()->where("apitoken='" . $headers['apitoken'] . "'")->one();
            if (isset($user->login)) {
                if ((time() - $user->apitime) < 86400) {
                    return $user->user_role;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
        return null;
    }


    }
}