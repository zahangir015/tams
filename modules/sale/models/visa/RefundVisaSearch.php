<?php

namespace app\modules\sale\models\visa;

use app\modules\account\models\Invoice;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * VisaSearch represents the model behind the search form of `app\modules\sale\models\visa\Visa`.
 */
class RefundVisaSearch extends Visa
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
            [['id', 'motherId', 'invoiceId', 'customerId', 'totalQuantity', 'processStatus', 'isOnlineBooked', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['invoice', 'customer', 'identificationNumber', 'customerCategory', 'type', 'issueDate', 'refundRequestDate', 'paymentStatus', 'reference', 'isRefunded', 'refundStatus', 'refundDate', 'refundMedium', 'refundMethod'], 'safe'],
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
        $query = Visa::find();

        // add conditions that should always apply here
        $query->joinWith(['invoice', 'customer', 'visaSuppliers',
            'visaRefund' => function ($query) {
                $query->where(['LIKE', 'refModel', 'Customer']);
            }])->where([self::tableName() . '.type' => ServiceConstant::TYPE['Refund']]);

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
            'asc' => [VisaRefund::tableName() . '.isRefunded' => SORT_ASC],
            'desc' => [VisaRefund::tableName() . '.isRefunded' => SORT_DESC],
        ];

        /*$dataProvider->sort->attributes['refundFromSupplierStatus'] = [
            'asc' => [VisaRefund::tableName() . '.refundFromSupplierStatus' => SORT_ASC],
            'desc' => [VisaRefund::tableName() . '.refundFromSupplierStatus' => SORT_DESC],
        ];*/

        $dataProvider->sort->attributes['refundStatus'] = [
            'asc' => [VisaRefund::tableName() . '.refundStatus' => SORT_ASC],
            'desc' => [VisaRefund::tableName() . '.refundStatus' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundMedium'] = [
            'asc' => [VisaRefund::tableName() . '.refundMedium' => SORT_ASC],
            'desc' => [VisaRefund::tableName() . '.refundMedium' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundMethod'] = [
            'asc' => [VisaRefund::tableName() . '.refundMethod' => SORT_ASC],
            'desc' => [VisaRefund::tableName() . '.refundMethod' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundDate'] = [
            'asc' => [VisaRefund::tableName() . '.refundDate' => SORT_ASC],
            'desc' => [VisaRefund::tableName() . '.refundDate' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundedAmount'] = [
            'asc' => [VisaRefund::tableName() . '.refundedAmount' => SORT_ASC],
            'desc' => [VisaRefund::tableName() . '.refundedAmount' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['serviceCharge'] = [
            'asc' => [VisaRefund::tableName() . '.serviceCharge' => SORT_ASC],
            'desc' => [VisaRefund::tableName() . '.serviceCharge' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['supplierRefundCharge'] = [
            'asc' => [VisaRefund::tableName() . '.supplierRefundCharge' => SORT_ASC],
            'desc' => [VisaRefund::tableName() . '.supplierRefundCharge' => SORT_DESC],
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
            self::tableName().'.totalQuantity' => $this->totalQuantity,
            self::tableName().'.processStatus' => $this->processStatus,
            self::tableName().'.quoteAmount' => $this->quoteAmount,
            self::tableName().'.costOfSale' => $this->costOfSale,
            self::tableName().'.netProfit' => $this->netProfit,
            self::tableName().'.receivedAmount' => $this->receivedAmount,
            self::tableName().'.isOnlineBooked' => $this->isOnlineBooked,
            VisaRefund::tableName() . '.isRefunded' => $this->isRefunded,
            VisaRefund::tableName() . '.refundedAmount' => $this->refundedAmount,
            //VisaRefund::tableName() . '.refundFromSupplierStatus' => $this->refundFromSupplierStatus,
            VisaRefund::tableName() . '.refundStatus' => $this->refundStatus,
            VisaRefund::tableName() . '.refundDate' => $this->refundDate,
            VisaRefund::tableName() . '.serviceCharge' => $this->serviceCharge,
            VisaRefund::tableName() . '.supplierRefundCharge' => $this->supplierRefundCharge,
            VisaRefund::tableName() . '.refundMedium' => $this->refundMedium,
            VisaRefund::tableName() . '.refundMethod' => $this->refundMethod,
            /*self::tableName().'.status' => $this->status,
            self::tableName().'.createdBy' => $this->createdBy,
            self::tableName().'.createdAt' => $this->createdAt,
            self::tableName().'.updatedBy' => $this->updatedBy,
            self::tableName().'.updatedAt' => $this->updatedAt,*/
        ]);

        $query->andFilterWhere(['like', Invoice::tableName() . '.invoiceNumber', $this->invoice])
            ->andFilterWhere(['like', Customer::tableName() . '.company', $this->customer])
            ->orFilterWhere(['like', Customer::tableName() . '.customerCode', $this->customer])
            ->andFilterWhere(['like', 'identificationNumber', $this->identificationNumber])
            ->andFilterWhere(['like', 'customerCategory', $this->customerCategory])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'paymentStatus', $this->paymentStatus])
            ->andFilterWhere(['like', 'reference', $this->reference]);

        return $dataProvider;
    }
}
