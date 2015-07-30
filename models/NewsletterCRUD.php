<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Newsletter;

/**
 * NewsletterCRUD represents the model behind the search form about `app\models\Newsletter`.
 */
class NewsletterCRUD extends Newsletter
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
        return Model::scenarios();
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
        $query = Newsletter::find();

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
            'newsletter_id' => $this->newsletter_id,
        ]);

        $query->andFilterWhere(['like', 'news', $this->news]);

        return $dataProvider;
    }

}
