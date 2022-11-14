<?php

namespace app\modules\sale\models\holiday;

use app\modules\account\models\Invoice;
use app\modules\sale\components\ServiceConstant;
use app\modules\sale\models\Customer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * HolidaySearch represents the model behind the search form of `app\modules\sale\models\holiday\Holiday`.
 */
class RefundHolidaySearch extends Holiday
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
            [['id', 'motherId', 'holidayCategoryId', 'isOnlineBooked', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['invoice', 'customer', 'identificationNumber', 'customerCategory', 'type', 'issueDate', 'departureDate', 'refundRequestDate', 'paymentStatus', 'route', 'isRefunded', 'refundStatus', 'refundDate', 'refundMedium', 'refundMethod'], 'safe'],
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
        $query = Holiday::find();

        // add conditions that should always apply here
        $query->joinWith(['customer', 'invoice', 'holidaySuppliers',
            'holidayRefund' => function ($query) {
                $query->where(['LIKE', 'refModel', 'Customer']);
            }])->where([self::tableName().'.type' => ServiceConstant::TYPE['Refund']]);

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
            'asc' => [HolidayRefund::tableName() . '.isRefunded' => SORT_ASC],
            'desc' => [HolidayRefund::tableName() . '.isRefunded' => SORT_DESC],
        ];

        /*$dataProvider->sort->attributes['refundFromSupplierStatus'] = [
            'asc' => [HolidayRefund::tableName() . '.refundFromSupplierStatus' => SORT_ASC],
            'desc' => [HolidayRefund::tableName() . '.refundFromSupplierStatus' => SORT_DESC],
        ];*/

        $dataProvider->sort->attributes['refundStatus'] = [
            'asc' => [HolidayRefund::tableName() . '.refundStatus' => SORT_ASC],
            'desc' => [HolidayRefund::tableName() . '.refundStatus' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundMedium'] = [
            'asc' => [HolidayRefund::tableName() . '.refundMedium' => SORT_ASC],
            'desc' => [HolidayRefund::tableName() . '.refundMedium' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundMethod'] = [
            'asc' => [HolidayRefund::tableName() . '.refundMethod' => SORT_ASC],
            'desc' => [HolidayRefund::tableName() . '.refundMethod' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundDate'] = [
            'asc' => [HolidayRefund::tableName() . '.refundDate' => SORT_ASC],
            'desc' => [HolidayRefund::tableName() . '.refundDate' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['refundedAmount'] = [
            'asc' => [HolidayRefund::tableName() . '.refundedAmount' => SORT_ASC],
            'desc' => [HolidayRefund::tableName() . '.refundedAmount' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['serviceCharge'] = [
            'asc' => [HolidayRefund::tableName() . '.serviceCharge' => SORT_ASC],
            'desc' => [HolidayRefund::tableName() . '.serviceCharge' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['supplierRefundCharge'] = [
            'asc' => [HolidayRefund::tableName() . '.supplierRefundCharge' => SORT_ASC],
            'desc' => [HolidayRefund::tableName() . '.supplierRefundCharge' => SORT_DESC],
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
            self::tableName().'.holidayCategoryId' => $this->holidayCategoryId,
            self::tableName().'.issueDate' => $this->issueDate,
            self::tableName().'.departureDate' => $this->departureDate,
            self::tableName().'.refundRequestDate' => $this->refundRequestDate,
            self::tableName().'.quoteAmount' => $this->quoteAmount,
            self::tableName().'.costOfSale' => $this->costOfSale,
            self::tableName().'.netProfit' => $this->netProfit,
            self::tableName().'.receivedAmount' => $this->receivedAmount,
            self::tableName().'.isOnlineBooked' => $this->isOnlineBooked,
            self::tableName().'.status' => $this->status,
            HolidayRefund::tableName() . '.isRefunded' => $this->isRefunded,
            HolidayRefund::tableName() . '.refundedAmount' => $this->refundedAmount,
            //HolidayRefund::tableName() . '.refundFromSupplierStatus' => $this->refundFromSupplierStatus,
            HolidayRefund::tableName() . '.refundStatus' => $this->refundStatus,
            HolidayRefund::tableName() . '.refundDate' => $this->refundDate,
            HolidayRefund::tableName() . '.serviceCharge' => $this->serviceCharge,
            HolidayRefund::tableName() . '.supplierRefundCharge' => $this->supplierRefundCharge,
            HolidayRefund::tableName() . '.refundMedium' => $this->refundMedium,
            HolidayRefund::tableName() . '.refundMethod' => $this->refundMethod,
            /*self::tableName().'.createdBy' => $this->createdBy,
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
            ->andFilterWhere(['like', 'route', $this->route]);

        return $dataProvider;
    }
}
