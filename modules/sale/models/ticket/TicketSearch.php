<?php

namespace app\modules\sale\models\ticket;

use app\components\GlobalConstant;
use app\modules\account\models\Invoice;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Airline;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Provider;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TicketSearch represents the model behind the search form of `app\modules\sale\models\Ticket`.
 */
class TicketSearch extends Ticket
{
    public $airline;
    public $provider;
    public $customer;
    public $invoice;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'motherTicketId', 'airlineId', 'providerId', 'invoiceId', 'customerId', 'bookedOnline', 'flightType', 'codeShare', 'numberOfSegment', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid', 'customerCategory', 'paxName', 'paxType', 'eTicket', 'pnrCode', 'type', 'tripType', 'seatClass', 'reference', 'issueDate', 'departureDate', 'refundRequestDate', 'route', 'paymentStatus', 'baggage', 'customer', 'airline', 'provider', 'invoice', 'refundPolicy'], 'safe'],
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

        // do we have values? if so, add a filter to our query
        if (isset($params['TicketSearch'])) {
            if (!empty($params['TicketSearch']['issueDate']) && str_contains($params['TicketSearch']['issueDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['TicketSearch']['issueDate']);
                $query->andFilterWhere(['between', 'issueDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
            if (!empty($params['TicketSearch']['departureDate']) && str_contains($params['TicketSearch']['departureDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['TicketSearch']['departureDate']);
                $query->andFilterWhere(['between', 'departureDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
            if (!empty($params['TicketSearch']['refundRequestDate']) && str_contains($params['TicketSearch']['refundRequestDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['TicketSearch']['refundRequestDate']);
                $query->andFilterWhere(['between', 'refundRequestDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
        }

        // add conditions that should always apply here
        $query->joinWith(['airline', 'customer', 'provider', 'invoice'])
            ->where(['<>', 'type', ServiceConstant::TYPE['Refund']])
            ->andWhere([self::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([self::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['issueDate' => SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['customer'] = [
            'asc' => [Customer::tableName() . '.company' => SORT_ASC],
            'desc' => [Customer::tableName() . '.company' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['provider'] = [
            'asc' => [Provider::tableName() . '.name' => SORT_ASC],
            'desc' => [Provider::tableName() . '.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['airline'] = [
            'asc' => [Airline::tableName() . '.name' => SORT_ASC],
            'desc' => [Airline::tableName() . '.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['invoice'] = [
            'asc' => [Invoice::tableName() . '.invoiceNumber' => SORT_ASC],
            'desc' => [Invoice::tableName() . '.invoiceNumber' => SORT_DESC],
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
            'motherTicketId' => $this->motherTicketId,
            'airlineId' => $this->airlineId,
            'providerId' => $this->providerId,
            'invoiceId' => $this->invoiceId,
            'customerId' => $this->customerId,
            'bookedOnline' => $this->bookedOnline,
            'flightType' => $this->flightType,
            'refundPolicy' => $this->refundPolicy,
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
            ->andFilterWhere(['like', Customer::tableName() . '.company', $this->customer])
            ->orFilterWhere(['like', Customer::tableName() . '.customerCode', $this->customer])
            ->andFilterWhere(['like', Airline::tableName() . '.name', $this->airline])
            ->orFilterWhere(['like', Airline::tableName() . '.code', $this->airline])
            ->andFilterWhere(['like', Provider::tableName() . '.name', $this->provider])
            ->andFilterWhere(['like', Invoice::tableName() . '.invoiceNumber', $this->invoice])
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
