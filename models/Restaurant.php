<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\base\Model;

class Restaurant extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_READ = 'read';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';

    public static function tableName()
    {
        return 'restaurants';
    }

    public function getReview()
    {
        return $this->hasMany(Review::className(), ['restaurant_id' => 'restaurant_id']);
    }

    public function getWarehouse()
    {
        return $this->hasMany(Warehouse::className(), ['restaurant_id' => 'restaurant_id']);
    }

    public function getOrder()
    {
        return $this->hasMany(Order::className(), ['restaurant_id' => 'restaurant_id']);
    }


    /**
     * @return array
     * scenarios:
     */
    public function rules(){
        return [
            [['name','street','house_nr'], 'required', 'on' => [self::SCENARIO_CREATE,self::SCENARIO_UPDATE]],
            ['restaurant_id','unique', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['restaurant_id', 'house_nr', 'flat_nr'], 'integer'],
            [['name', 'street', 'house_nr', 'flat_nr', 'zip_code', 'city'], 'safe'],
            [['name', 'street','zip_code','city'],'string'],
            ['zip_code', 'string', 'max' => 6, 'min' => 6 ],
            ['zip_code', 'match', 'pattern'=>'/^\d{2}-\d{3}$/']
        ];
    }

    /**
     * @return array
     */
    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['restaurant_id', 'name', 'street', 'house_nr', 'flat_nr', 'zip_code', 'city'];
        $scenarios[self::SCENARIO_READ] = ['restaurant_id', 'name', 'street', 'house_nr', 'flat_nr', 'zip_code', 'city'];
        $scenarios[self::SCENARIO_UPDATE] = ['restaurant_id', 'name', 'street', 'house_nr', 'flat_nr', 'zip_code', 'city'];
        return $scenarios;
    }
}
