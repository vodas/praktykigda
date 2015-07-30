<?php

namespace app\models;

use yii\db\ActiveRecord;

class Warehouse extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_READ = 'read';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';

    public static function tableName()
    {
        return 'warehouse';
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
            ['price','number'],
            [['product_id','restaurant_id'],'integer'],
            ['restaurant_id', 'exist', 'targetClass' => '\app\models\Restaurant', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            ['product_id', 'exist', 'targetClass' => '\app\models\Product', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['restaurant_id','product_id','price'], 'required', 'on' => self::SCENARIO_CREATE],
            [['restaurant_id','product_id','price'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['warehouse_id', 'restaurant_id', 'product_id', 'description', 'price'], 'safe']
        ];
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['warehouse_id', 'restaurant_id', 'product_id', 'description','price'];
        $scenarios[self::SCENARIO_READ] = ['warehouse_id', 'restaurant_id', 'product_id', 'description','price'];
        $scenarios[self::SCENARIO_UPDATE] = ['warehouse_id', 'restaurant_id', 'product_id', 'description','price'];
        return $scenarios;
    }
}