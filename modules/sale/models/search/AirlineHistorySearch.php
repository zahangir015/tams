<?php

namespace app\modules\sale\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sale\models\AirlineHistory;

/**
 * AirlineHistorySearch represents the model behind the search form of `app\modules\sale\models\AirlineHistory`.
 */
class AirlineHistorySearch extends AirlineHistory
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'airlineId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'safe'],
            [['commission', 'incentive', 'govTax', 'serviceCharge'], 'number'],
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
        $query = AirlineHistory::find();

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
            'airlineId' => $this->airlineId,
            'commission' => $this->commission,
            'incentive' => $this->incentive,
            'govTax' => $this->govTax,
            'serviceCharge' => $this->serviceCharge,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid]);

        return $dataProvider;
    }
}
