<?php

namespace app\modules\agent\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\agent\models\Agency;

/**
 * AgencySearch represents the model behind the search form of `app\modules\agent\models\Agency`.
 */
class AgencySearch extends Agency
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'planId', 'countryId', 'cityId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'agentCode', 'company', 'address', 'phone', 'email', 'timeZone', 'currency', 'title', 'firstName', 'lastName'], 'safe'],
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
        $query->joinWith(['plan']);

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
            'planId' => $this->planId,
            'countryId' => $this->countryId,
            'cityId' => $this->cityId,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
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
