<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CountrySearch represents the model behind the search form of `app\modules\sale\models\Country`.
 */
class CountrySearch extends Country
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'name', 'code'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
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
    public function search(array $params): ActiveDataProvider
    {
        $query = Country::find();

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
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
