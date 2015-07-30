<?php

namespace app\models;

use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_READ = 'read';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';

    public function giveAllProductsByName($name) {
        $connection = \Yii::$app->db;
        $results = $connection->createCommand("SELECT DISTINCT a.name, a.city, a.street, a.house_nr, a.flat_nr, a.restaurant_id FROM restaurants a, products b, warehouse c WHERE b.name LIKE '%".$name."%' AND c.product_id=b.product_id AND c.restaurant_id=a.restaurant_id")->queryAll();
        return $results;
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['product_id', 'name', 'description'];
        $scenarios[self::SCENARIO_READ] = ['product_id', 'name', 'description'];
        $scenarios[self::SCENARIO_UPDATE] = ['product_id', 'name', 'description'];
        return $scenarios;

    }

    public static function tableName()
    {
        return 'products';
    }

    public function getReview()
    {
        return $this->hasMany(Review::className(), ['product_id' => 'product_id']);
    }

    public function getWarehouse()
    {
        return $this->hasMany(Warehouse::className(), ['product_id' => 'product_id']);
    }

    public function rules(){
        return [
            ['product_id','unique', 'on' => [self::SCENARIO_CREATE]],
            [['name','description'],'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_CREATE]],
            [['name','description'], 'string'],
            [['product_id', 'name', 'description'], 'safe']
        ];
    }
}