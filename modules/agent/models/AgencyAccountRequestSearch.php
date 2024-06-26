<?php

namespace app\modules\agent\models;

use app\models\City;
use app\models\Country;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\agent\models\AgencyAccountRequest;

/**
 * AgencyAccountRequestSearch represents the model behind the search form of `app\modules\agent\models\AgencyAccountRequest`.
 */
class AgencyAccountRequestSearch extends AgencyAccountRequest
{
    public $country;
    public $city;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'countryId', 'cityId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'name', 'designation', 'company', 'address', 'phone', 'email', 'country', 'city'], 'safe'],
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
        $query = AgencyAccountRequest::find();

        // add conditions that should always apply here
        $query->joinWith(['country', 'city']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['country'] = [
            'asc' => [Country::tableName() . '.name' => SORT_ASC],
            'desc' => [Country::tableName() . '.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['city'] = [
            'asc' => [City::tableName() . '.name' => SORT_ASC],
            'desc' => [City::tableName() . '.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'countryId' => $this->countryId,
            'cityId' => $this->cityId,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', Country::tableName() . '.name', $this->country])
            ->orFilterWhere(['like', Country::tableName() . '.code', $this->country])
            ->andFilterWhere(['like', City::tableName() . '.name', $this->city])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'designation', $this->designation])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
