<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Menu;

/**
 * MenuSearch represents the model behind the search form of `backend\models\Menu`.
 */
class MenuSearch extends Menu
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'qty', 'categories_id', 'user_id'], 'integer'],
            [['photo', 'name'], 'safe'],
            [['prices'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Menu::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'qty' => $this->qty,
            'prices' => $this->prices,
            'categories_id' => $this->categories_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function saleProductSearch($params)
    {
        $query = Menu::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'qty' => $this->qty,
            'prices' => $this->prices,
            'categories_id' => $this->categories_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }


    public function printbill($params)
    {
        $query = Menu::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'qty' => $this->qty,
            'prices' => $this->prices,
            'categories_id' => $this->categories_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
