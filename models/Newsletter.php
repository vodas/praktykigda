<?php

namespace app\models;
use yii\db\ActiveRecord;
use Yii;

class Newsletter extends ActiveRecord
{

    public static function tableName()
    {
        return 'newsletter';
    }


    public function rules(){
        return [
            //['news','required'],
            [['news','sent'],'safe']
        ];
    }


}