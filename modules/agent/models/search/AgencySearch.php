<?php

namespace app\modules\agent\models\search;

use app\models\City;
use app\models\Country;
use app\modules\agent\models\Plan;
use app\modules\sale\models\Airline;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Provider;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\agent\models\Agency;

/**
 * AgencySearch represents the model behind the search form of `app\modules\agent\models\Agency`.
 */
class AgencySearch extends Agency
{
    public $country;
    public $city;
    public $plan;
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'planId', 'countryId', 'cityId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'agentCode', 'company', 'address', 'phone', 'email', 'timeZone', 'currency', 'title', 'firstName', 'lastName', 'country', 'city', 'plan'], 'safe'],
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
        $query = Agency::find();

        // add conditions that should always apply here
        $query->joinWith(['country', 'city', 'plan']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_ASC]]
        ]);

        $dataProvider->sort->attributes['country'] = [
            'asc' => [Country::tableName() . '.name' => SORT_ASC],
            'desc' => [Country::tableName() . '.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['city'] = [
            'asc' => [City::tableName() . '.name' => SORT_ASC],
            'desc' => [City::tableName() . '.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['plan'] = [
            'asc' => [Plan::tableName() . '.name' => SORT_ASC],
            'desc' => [Plan::tableName() . '.name' => SORT_DESC],
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
            'planId' => $this->planId,
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
            ->andFilterWhere(['like', Plan::tableName() . '.name', $this->plan])
            ->andFilterWhere(['like', 'agentCode', $this->agentCode])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'timeZone', $this->timeZone])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'firstName', $this->firstName])
            ->andFilterWhere(['like', 'lastName', $this->lastName]);

        return $dataProvider;
    }
}
