<?php

namespace app\modules\sale\models\hotel;

use app\modules\account\models\Invoice;
use app\modules\sale\components\AccountConstant;
use app\modules\sale\models\Customer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * HotelSearch represents the model behind the search form of `app\modules\sale\models\hotel\Hotel`.
 */
class RefundHotelSearch extends Hotel
{
    public $invoice;
    public $customer;
    public $isRefunded;
    public $refundedAmount;
    public $refundFromSupplierStatus;
    public $refundStatus;
    public $refundDate;
    public $serviceCharge;
    public $supplierRefundCharge;
    public $refundMedium;
    public $refundMethod;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'motherId', 'invoiceId', 'customerId', 'totalNights', 'isRefundable', 'isOnlineBooked', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['invoice', 'customer', 'identificationNumber', 'customerCategory', 'voucherNumber', 'reservationCode', 'type', 'issueDate', 'refundRequestDate', 'checkInDate', 'checkOutDate', 'freeCancellationDate', 'route', 'paymentStatus', 'reference', 'isRefunded', 'refundStatus', 'refundDate', 'refundMedium', 'refundMethod'], 'safe'],
            [['quoteAmount', 'costOfSale', 'netProfit', 'receivedAmount', 'refundedAmount', 'serviceCharge', 'supplierRefundCharge'], 'number'],
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
        $query->joinWith(['invoice', 'customer', 'hotelSuppliers',
            'hotelRefund' => function ($query) {
                $query->where(['LIKE', 'refModel', 'Customer']);
            }])->where([self::tableName() . '.type' => AccountConstant::TYPE['Refund']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['issueDate' => SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['invoice'] = [
            'asc' => [Invoice::tableName() . '.invoiceNumber' => SORT_ASC],
            'desc' => [Invoice::tableName() . '.invoiceNumber' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['customer'] = [
            'asc' => [Customer::tableName() . '.company' => SORT_ASC],
            'desc' => [Customer::tableName() . '.company' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['isRefunded'] = [
            'asc' => [HotelRefund::tableName() . '.isRefunded' => SORT_ASC],
            'desc' => [HotelRefund::tableName() . '.isRefunded' => SORT_DESC],
        ];

        /*$dataProvider->sort->attributes['refundFromSupplierStatus'] = [
            'asc' => [HotelRefund::tableName() . '.refundFromSupplierStatus' => SORT_ASC],
            'desc' => [HotelRefund::tableName() . '.refundFromSupplierStatus' => SORT_DESC],
        ];*/

        $dataProvider->sort->attributes['refundStatus'] = [
            'asc' => [HotelRefund::tableName() . '.refundStatus' => SORT_ASC],
            'desc' => [HotelRefund::tableName() . '.refundStatus' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundMedium'] = [
            'asc' => [HotelRefund::tableName() . '.refundMedium' => SORT_ASC],
            'desc' => [HotelRefund::tableName() . '.refundMedium' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundMethod'] = [
            'asc' => [HotelRefund::tableName() . '.refundMethod' => SORT_ASC],
            'desc' => [HotelRefund::tableName() . '.refundMethod' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundDate'] = [
            'asc' => [HotelRefund::tableName() . '.refundDate' => SORT_ASC],
            'desc' => [HotelRefund::tableName() . '.refundDate' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundedAmount'] = [
            'asc' => [HotelRefund::tableName() . '.refundedAmount' => SORT_ASC],
            'desc' => [HotelRefund::tableName() . '.refundedAmount' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['serviceCharge'] = [
            'asc' => [HotelRefund::tableName() . '.serviceCharge' => SORT_ASC],
            'desc' => [HotelRefund::tableName() . '.serviceCharge' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['supplierRefundCharge'] = [
            'asc' => [HotelRefund::tableName() . '.supplierRefundCharge' => SORT_ASC],
            'desc' => [HotelRefund::tableName() . '.supplierRefundCharge' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            self::tableName().'.motherId' => $this->motherId,
            self::tableName().'.invoiceId' => $this->invoiceId,
            self::tableName().'.customerId' => $this->customerId,
            self::tableName().'.issueDate' => $this->issueDate,
            self::tableName().'.refundRequestDate' => $this->refundRequestDate,
            self::tableName().'.checkInDate' => $this->checkInDate,
            self::tableName().'.checkOutDate' => $this->checkOutDate,
            self::tableName().'.freeCancellationDate' => $this->freeCancellationDate,
            self::tableName().'.totalNights' => $this->totalNights,
            self::tableName().'.isRefundable' => $this->isRefundable,
            self::tableName().'.quoteAmount' => $this->quoteAmount,
            self::tableName().'.costOfSale' => $this->costOfSale,
            self::tableName().'.netProfit' => $this->netProfit,
            self::tableName().'.receivedAmount' => $this->receivedAmount,
            self::tableName().'.isOnlineBooked' => $this->isOnlineBooked,
            HotelRefund::tableName() . '.isRefunded' => $this->isRefunded,
            HotelRefund::tableName() . '.refundedAmount' => $this->refundedAmount,
            //HotelRefund::tableName() . '.refundFromSupplierStatus' => $this->refundFromSupplierStatus,
            HotelRefund::tableName() . '.refundStatus' => $this->refundStatus,
            HotelRefund::tableName() . '.refundDate' => $this->refundDate,
            HotelRefund::tableName() . '.serviceCharge' => $this->serviceCharge,
            HotelRefund::tableName() . '.supplierRefundCharge' => $this->supplierRefundCharge,
            HotelRefund::tableName() . '.refundMedium' => $this->refundMedium,
            HotelRefund::tableName() . '.refundMethod' => $this->refundMethod,
            /*'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,*/
        ]);

        $query->andFilterWhere(['like', Invoice::tableName() . '.invoiceNumber', $this->invoice])
            ->andFilterWhere(['like', Customer::tableName() . '.company', $this->customer])
            ->orFilterWhere(['like', Customer::tableName() . '.customerCode', $this->customer])
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
