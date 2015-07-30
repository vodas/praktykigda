<?php

namespace app\models;

use yii\db\ActiveRecord;

class UserRole extends ActiveRecord
{


    public static function tableName()
    {
        return 'users_role';
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    public function rules(){
        return [
            [['user_id'],'exist'],
            ['role_id',['required']]
        ];
    }
}