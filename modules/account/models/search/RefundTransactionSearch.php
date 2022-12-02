<?php

namespace app\modules\account\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\account\models\RefundTransaction;

/**
 * RefundTransactionSearch represents the model behind the search form of `app\modules\account\models\RefundTransaction`.
 */
class RefundTransactionSearch extends RefundTransaction
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'refId', 'isAdjusted', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'refModel', 'paymentStatus', 'remarks', 'identificationNumber'], 'safe'],
            [['payableAmount', 'receivableAmount', 'totalAmount', 'adjustedAmount'], 'number'],
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
        $query = RefundTransaction::find();

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
            'refId' => $this->refId,
            'payableAmount' => $this->payableAmount,
            'receivableAmount' => $this->receivableAmount,
            'totalAmount' => $this->totalAmount,
            'adjustedAmount' => $this->adjustedAmount,
            'isAdjusted' => $this->isAdjusted,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'refModel', $this->refModel])
            ->andFilterWhere(['like', 'paymentStatus', $this->paymentStatus])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'identificationNumber', $this->identificationNumber]);

        return $dataProvider;
    }
}
