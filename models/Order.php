<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\Restaurant;


class Order extends ActiveRecord
{
    const STATUS_CREATED = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_CONFIRMED = 3;
    const STATUS_DELIVER = 4;
    const STATUS_DONE = 5;
    const STATUS_DELETE = 6;


    const SCENARIO_CREATE = 'create';
    const SCENARIO_READ = 'read';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';

    public static function tableName()
    {
        return 'orders';
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_id' => 'restaurant_id']);
    }

    public function rules(){
        return [
            ['restaurant_id', 'exist', 'targetClass' => '\app\models\Restaurant', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            ['user_id', 'exist', 'targetClass' => '\app\models\User', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['status','user_id','restaurant_id'], 'integer'],
            [['order_id', 'user_id', 'user_info', 'restaurant_id', 'order_info', 'status', 'other_users'], 'safe'],
            ['order_id', 'unique', 'on' => self::SCENARIO_CREATE],
            [['restaurant_id', 'order_info', 'status'], 'required', 'on' => self::SCENARIO_CREATE],
            [['restaurant_id', 'order_info', 'status'], 'required', 'on' => self::SCENARIO_UPDATE],
        ];
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['order_id', 'user_id', 'user_info', 'restaurant_id', 'order_info', 'status', 'other_users'];
        $scenarios[self::SCENARIO_READ] = ['order_id', 'user_id', 'user_info', 'restaurant_id', 'order_info', 'status', 'other_users'];
        $scenarios[self::SCENARIO_UPDATE] = ['order_id', 'user_id', 'user_info', 'restaurant_id', 'order_info', 'status', 'other_users'];
        return $scenarios;
    }

}