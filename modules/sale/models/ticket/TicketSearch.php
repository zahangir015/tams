<?php

namespace app\modules\sale\models\ticket;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TicketSearch represents the model behind the search form of `app\modules\sale\models\Ticket`.
 */
class TicketSearch extends Ticket
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'motherTicketId', 'airlineId', 'providerId', 'invoiceId', 'customerId', 'bookedOnline', 'flightType', 'codeShare', 'numberOfSegment', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'customerCategory', 'paxName', 'paxType', 'eTicket', 'pnrCode', 'type', 'tripType', 'seatClass', 'reference', 'issueDate', 'departureDate', 'refundRequestDate', 'route', 'paymentStatus', 'baggage'], 'safe'],
            [['baseFare', 'tax', 'otherTax', 'commission', 'commissionReceived', 'incentive', 'incentiveReceived', 'govTax', 'serviceCharge', 'ait', 'quoteAmount', 'receivedAmount', 'costOfSale', 'netProfit'], 'number'],
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
        $query = Ticket::find();

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
            'motherTicketId' => $this->motherTicketId,
            'airlineId' => $this->airlineId,
            'providerId' => $this->providerId,
            'invoiceId' => $this->invoiceId,
            'customerId' => $this->customerId,
            'bookedOnline' => $this->bookedOnline,
            'flightType' => $this->flightType,
            'codeShare' => $this->codeShare,
            'issueDate' => $this->issueDate,
            'departureDate' => $this->departureDate,
            'refundRequestDate' => $this->refundRequestDate,
            'numberOfSegment' => $this->numberOfSegment,
            'baseFare' => $this->baseFare,
            'tax' => $this->tax,
            'otherTax' => $this->otherTax,
            'commission' => $this->commission,
            'commissionReceived' => $this->commissionReceived,
            'incentive' => $this->incentive,
            'incentiveReceived' => $this->incentiveReceived,
            'govTax' => $this->govTax,
            'serviceCharge' => $this->serviceCharge,
            'ait' => $this->ait,
            'quoteAmount' => $this->quoteAmount,
            'receivedAmount' => $this->receivedAmount,
            'costOfSale' => $this->costOfSale,
            'netProfit' => $this->netProfit,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'customerCategory', $this->customerCategory])
            ->andFilterWhere(['like', 'paxName', $this->paxName])
            ->andFilterWhere(['like', 'paxType', $this->paxType])
            ->andFilterWhere(['like', 'eTicket', $this->eTicket])
            ->andFilterWhere(['like', 'pnrCode', $this->pnrCode])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'tripType', $this->tripType])
            ->andFilterWhere(['like', 'seatClass', $this->seatClass])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'paymentStatus', $this->paymentStatus])
            ->andFilterWhere(['like', 'baggage', $this->baggage]);

        return $dataProvider;
    }
}
