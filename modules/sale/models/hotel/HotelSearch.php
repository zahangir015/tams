<?php

namespace app\modules\sale\models\hotel;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sale\models\hotel\Hotel;

/**
 * HotelSearch represents the model behind the search form of `app\modules\sale\models\hotel\Hotel`.
 */
class HotelSearch extends Hotel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'motherId', 'invoiceId', 'customerId', 'totalNights', 'isRefundable', 'isOnlineBooked', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'identificationNumber', 'customerCategory', 'voucherNumber', 'reservationCode', 'type', 'issueDate', 'refundRequestDate', 'checkInDate', 'checkOutDate', 'freeCancellationDate', 'route', 'paymentStatus', 'reference'], 'safe'],
            [['quoteAmount', 'costOfSale', 'netProfit', 'receivedAmount'], 'number'],
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
        $query = Hotel::find();

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
            'motherId' => $this->motherId,
            'invoiceId' => $this->invoiceId,
            'customerId' => $this->customerId,
            'issueDate' => $this->issueDate,
            'refundRequestDate' => $this->refundRequestDate,
            'checkInDate' => $this->checkInDate,
            'checkOutDate' => $this->checkOutDate,
            'freeCancellationDate' => $this->freeCancellationDate,
            'totalNights' => $this->totalNights,
            'isRefundable' => $this->isRefundable,
            'quoteAmount' => $this->quoteAmount,
            'costOfSale' => $this->costOfSale,
            'netProfit' => $this->netProfit,
            'receivedAmount' => $this->receivedAmount,
            'isOnlineBooked' => $this->isOnlineBooked,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'identificationNumber', $this->identificationNumber])
            ->andFilterWhere(['like', 'customerCategory', $this->customerCategory])
            ->andFilterWhere(['like', 'voucherNumber', $this->voucherNumber])
            ->andFilterWhere(['like', 'reservationCode', $this->reservationCode])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'paymentStatus', $this->paymentStatus])
            ->andFilterWhere(['like', 'reference', $this->reference]);

        return $dataProvider;
    }
}
