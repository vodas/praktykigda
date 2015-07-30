<?php

namespace app\models;

use yii\db\ActiveRecord;

class Review extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_READ = 'read';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';


    public static function tableName()
    {
        return 'review';
    }

    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_id' => 'restaurant_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
    }


    public function rules(){
        return [
            ['restaurant_id', 'exist', 'targetClass' => '\app\models\Restaurant', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            ['product_id', 'exist', 'targetClass' => '\app\models\Product', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['restaurant_id','product_id'], 'integer'],
            [['restaurant_id','product_id','review'],'required', 'on' => self::SCENARIO_CREATE],
            [['restaurant_id','product_id','review'],'required', 'on' => self::SCENARIO_UPDATE],
            ['review','string']
        ];
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['review_id', 'restaurant_id', 'product_id','review'];
        $scenarios[self::SCENARIO_READ] = ['review_id', 'restaurant_id', 'product_id','review'];
        $scenarios[self::SCENARIO_UPDATE] = ['review_id', 'restaurant_id', 'product_id','review'];
        return $scenarios;
    }

}