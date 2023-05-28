<?php

namespace app\modules\sale\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sale\models\FlightProposal;

/**
 * FlightProposalSearch represents the model behind the search form of `app\modules\sale\models\FlightProposal`.
 */
class FlightProposalSearch extends FlightProposal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'agencyId', 'airlineId', 'tripType', 'numberOfAdult', 'numberOfChild', 'numberOfInfant', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'class', 'route', 'departure', 'arrival', 'baggagePerAdult', 'baggagePerChild', 'baggagePerInfant', 'notes'], 'safe'],
            [['pricePerAdult', 'pricePerChild', 'pricePerInfant', 'totalPrice', 'discount'], 'number'],
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
        $query = FlightProposal::find();

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
            'agencyId' => $this->agencyId,
            'airlineId' => $this->airlineId,
            'tripType' => $this->tripType,
            'departure' => $this->departure,
            'arrival' => $this->arrival,
            'numberOfAdult' => $this->numberOfAdult,
            'pricePerAdult' => $this->pricePerAdult,
            'numberOfChild' => $this->numberOfChild,
            'pricePerChild' => $this->pricePerChild,
            'numberOfInfant' => $this->numberOfInfant,
            'pricePerInfant' => $this->pricePerInfant,
            'totalPrice' => $this->totalPrice,
            'discount' => $this->discount,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'baggagePerAdult', $this->baggagePerAdult])
            ->andFilterWhere(['like', 'baggagePerChild', $this->baggagePerChild])
            ->andFilterWhere(['like', 'baggagePerInfant', $this->baggagePerInfant])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
