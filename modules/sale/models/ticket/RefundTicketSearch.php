<?php

namespace app\modules\sale\models\ticket;

use app\modules\account\models\Invoice;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Airline;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Provider;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TicketSearch represents the model behind the search form of `app\modules\sale\models\Ticket`.
 */
class RefundTicketSearch extends Ticket
{
    public $airline;
    public $provider;
    public $customer;
    public $invoice;
    public $isRefunded;
    public $refundedAmount;
    public $refundFromSupplierStatus;
    public $refundStatus;
    public $refundDate;
    public $serviceCharge;
    public $airlineRefundCharge;
    public $supplierRefundCharge;
    public $refundMedium;
    public $refundMethod;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'motherTicketId', 'airlineId', 'providerId', 'invoiceId', 'customerId', 'bookedOnline', 'flightType', 'codeShare', 'numberOfSegment', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['customerCategory', 'paxName', 'paxType', 'eTicket', 'pnrCode', 'type', 'tripType', 'seatClass', 'reference', 'issueDate', 'departureDate', 'refundRequestDate', 'route', 'paymentStatus', 'baggage', 'customer', 'airline', 'provider', 'invoice', 'refundFromSupplierStatus', 'isRefunded', 'refundStatus', 'refundDate', 'refundMedium', 'refundMethod'], 'safe'],
            [['baseFare', 'tax', 'otherTax', 'commission', 'commissionReceived', 'incentive', 'incentiveReceived', 'govTax', 'serviceCharge', 'ait', 'quoteAmount', 'receivedAmount', 'costOfSale', 'netProfit', 'refundedAmount', 'serviceCharge', 'airlineRefundCharge', 'supplierRefundCharge'], 'number'],
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
        if (isset($params['RefundTicketSearch'])) {
            if (!empty($params['RefundTicketSearch']['issueDate']) && str_contains($params['RefundTicketSearch']['issueDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['RefundTicketSearch']['issueDate']);
                $query->andFilterWhere(['between', 'issueDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
            if (!empty($params['RefundTicketSearch']['departureDate']) && str_contains($params['RefundTicketSearch']['departureDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['RefundTicketSearch']['departureDate']);
                $query->andFilterWhere(['between', 'departureDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
            if (!empty($params['RefundTicketSearch']['refundRequestDate']) && str_contains($params['RefundTicketSearch']['refundRequestDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['RefundTicketSearch']['refundRequestDate']);
                $query->andFilterWhere(['between', 'refundRequestDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
        }

        // add conditions that should always apply here
        $query->joinWith(['airline', 'customer', 'provider', 'invoice','ticketSupplier',
            'ticketRefund' => function ($query) {
                $query->where(['LIKE', 'refModel', 'Customer']);
            }])->where([self::tableName().'.type' => ServiceConstant::TYPE['Refund']]);

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

        $dataProvider->sort->attributes['isRefunded'] = [
            'asc' => [TicketRefund::tableName() . '.isRefunded' => SORT_ASC],
            'desc' => [TicketRefund::tableName() . '.isRefunded' => SORT_DESC],
        ];

        /*$dataProvider->sort->attributes['refundFromSupplierStatus'] = [
            'asc' => [TicketRefund::tableName() . '.refundFromSupplierStatus' => SORT_ASC],
            'desc' => [TicketRefund::tableName() . '.refundFromSupplierStatus' => SORT_DESC],
        ];*/

        $dataProvider->sort->attributes['refundStatus'] = [
            'asc' => [TicketRefund::tableName() . '.refundStatus' => SORT_ASC],
            'desc' => [TicketRefund::tableName() . '.refundStatus' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundMedium'] = [
            'asc' => [TicketRefund::tableName() . '.refundMedium' => SORT_ASC],
            'desc' => [TicketRefund::tableName() . '.refundMedium' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundMethod'] = [
            'asc' => [TicketRefund::tableName() . '.refundMethod' => SORT_ASC],
            'desc' => [TicketRefund::tableName() . '.refundMethod' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundDate'] = [
            'asc' => [TicketRefund::tableName() . '.refundDate' => SORT_ASC],
            'desc' => [TicketRefund::tableName() . '.refundDate' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundedAmount'] = [
            'asc' => [TicketRefund::tableName() . '.refundedAmount' => SORT_ASC],
            'desc' => [TicketRefund::tableName() . '.refundedAmount' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['serviceCharge'] = [
            'asc' => [TicketRefund::tableName() . '.serviceCharge' => SORT_ASC],
            'desc' => [TicketRefund::tableName() . '.serviceCharge' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['airlineRefundCharge'] = [
            'asc' => [TicketRefund::tableName() . '.airlineRefundCharge' => SORT_ASC],
            'desc' => [TicketRefund::tableName() . '.airlineRefundCharge' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['supplierRefundCharge'] = [
            'asc' => [TicketRefund::tableName() . '.supplierRefundCharge' => SORT_ASC],
            'desc' => [TicketRefund::tableName() . '.supplierRefundCharge' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            self::tableName() . '.motherTicketId' => $this->motherTicketId,
            self::tableName() . '.airlineId' => $this->airlineId,
            self::tableName() . '.providerId' => $this->providerId,
            self::tableName() . '.invoiceId' => $this->invoiceId,
            self::tableName() . '.customerId' => $this->customerId,
            self::tableName() . '.bookedOnline' => $this->bookedOnline,
            self::tableName() . '.flightType' => $this->flightType,
            self::tableName() . '.codeShare' => $this->codeShare,
            self::tableName() . '.issueDate' => $this->issueDate,
            self::tableName() . '.departureDate' => $this->departureDate,
            self::tableName() . '.refundRequestDate' => $this->refundRequestDate,
            self::tableName() . '.numberOfSegment' => $this->numberOfSegment,
            self::tableName() . '.baseFare' => $this->baseFare,
            self::tableName() . '.tax' => $this->tax,
            self::tableName() . '.otherTax' => $this->otherTax,
            self::tableName() . '.commission' => $this->commission,
            self::tableName() . '.commissionReceived' => $this->commissionReceived,
            self::tableName() . '.incentive' => $this->incentive,
            self::tableName() . '.incentiveReceived' => $this->incentiveReceived,
            self::tableName() . '.govTax' => $this->govTax,
            self::tableName() . '.serviceCharge' => $this->serviceCharge,
            self::tableName() . '.ait' => $this->ait,
            self::tableName() . '.quoteAmount' => $this->quoteAmount,
            self::tableName() . '.receivedAmount' => $this->receivedAmount,
            self::tableName() . '.costOfSale' => $this->costOfSale,
            self::tableName() . '.netProfit' => $this->netProfit,
            self::tableName() . '.status' => $this->status,
            TicketRefund::tableName() . '.isRefunded' => $this->isRefunded,
            TicketRefund::tableName() . '.refundedAmount' => $this->refundedAmount,
            //TicketRefund::tableName() . '.refundFromSupplierStatus' => $this->refundFromSupplierStatus,
            TicketRefund::tableName() . '.refundStatus' => $this->refundStatus,
            TicketRefund::tableName() . '.refundDate' => $this->refundDate,
            TicketRefund::tableName() . '.serviceCharge' => $this->serviceCharge,
            TicketRefund::tableName() . '.airlineRefundCharge' => $this->airlineRefundCharge,
            TicketRefund::tableName() . '.supplierRefundCharge' => $this->supplierRefundCharge,
            TicketRefund::tableName() . '.refundMedium' => $this->refundMedium,
            TicketRefund::tableName() . '.refundMethod' => $this->refundMethod,
            /*'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,*/
        ]);

        $query->andFilterWhere(['like', Customer::tableName() . '.company', $this->customer])
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
