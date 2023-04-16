<?php

namespace app\modules\sale\models\hotel;

use app\components\GlobalConstant;
use app\modules\account\models\Invoice;
use app\modules\sale\models\Customer;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sale\models\hotel\Hotel;

/**
 * HotelSearch represents the model behind the search form of `app\modules\sale\models\hotel\Hotel`.
 */
class HotelSearch extends Hotel
{
    public $invoice;
    public $customer;
    public $hotelSuppliers;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'motherId', 'invoiceId', 'customerId', 'totalNights', 'isRefundable', 'isOnlineBooked', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['invoice', 'customer', 'identificationNumber', 'customerCategory', 'voucherNumber', 'reservationCode', 'type', 'issueDate', 'refundRequestDate', 'checkInDate', 'checkOutDate', 'freeCancellationDate', 'route', 'paymentStatus', 'reference', 'hotelSuppliers'], 'safe'],
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
        $query->joinWith(['invoice', 'customer', 'hotelSuppliers'])
            ->where([self::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere(['agencyId' => Yii::$app->user->identity->agencyId]);

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

        $dataProvider->sort->attributes['hotelSuppliers'] = [
            'asc' => [HotelSupplier::tableName() . '.hotelName' => SORT_ASC],
            'desc' => [HotelSupplier::tableName() . '.hotelName' => SORT_DESC],
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
            ->andFilterWhere(['like', Invoice::tableName() . '.invoiceNumber', $this->invoice])
            ->andFilterWhere(['like', Customer::tableName() . '.company', $this->customer])
            ->orFilterWhere(['like', Customer::tableName() . '.customerCode', $this->customer])
            ->orFilterWhere(['like', HotelSupplier::tableName() . '.hotelName', $this->hotelSuppliers])
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
