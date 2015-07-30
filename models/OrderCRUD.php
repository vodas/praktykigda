<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderCRUD represents the model behind the search form about `app\models\Order`.
 */
class OrderCRUD extends Order
{
    /**
     * @inheritdoc
     */
   public function rules()
   {
       return parent::rules();
   }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return parent::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'restaurant_id' => $this->restaurant_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'user_info', $this->user_info])
            ->andFilterWhere(['like', 'order_info', $this->order_info])
            ->andFilterWhere(['like', 'other_users', $this->other_users]);

        return $dataProvider;
    }
}
