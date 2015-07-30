<?php
namespace app\components;

use Yii;

class PasswordHelper {
    static function checkPassword($modelCheck){
        if($modelCheck->pass=='' && empty($modelCheck->getOldAttribute('pass'))){
            $modelCheck->pass = $modelCheck->getOldAttribute('pass');
        }
        elseif($modelCheck->pass != '') {
            $modelCheck->pass = self::hashPassword($modelCheck->pass);
        }
        return $modelCheck;
    }

    static function hashPassword($password) {
        return ($password == '') ? '' :hash('sha256',Yii::$app->params['hashSalt'].$password);
    }
}