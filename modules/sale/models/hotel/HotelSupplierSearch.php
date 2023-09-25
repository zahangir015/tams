<?php

namespace app\modules\sale\models\hotel;

use app\components\GlobalConstant;
use app\modules\account\models\Bill;
use app\modules\sale\models\Supplier;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * HotelSupplierSearch represents the model behind the search form of `app\modules\sale\models\hotel\HotelSupplier`.
 */
class HotelSupplierSearch extends HotelSupplier
{
    public $bill;
    public $supplier;
    public $hotel;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'motherId', 'hotelId', 'billId', 'supplierId', 'numberOfNights', 'quantity', 'status', 'motherId'], 'integer'],
            [['bill', 'supplier', 'hotel', 'supplierRef', 'issueDate', 'refundRequestDate', 'type', 'serviceDetails', 'paymentStatus', 'hotelName', 'roomType'], 'safe'],
            [['unitPrice', 'costOfSale', 'paidAmount'], 'number'],
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
        $query = HotelSupplier::find();

        // add conditions that should always apply here
        $query->joinWith(['bill', 'supplier' => function ($query) {
            $query->where([Supplier::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId]);
        }, 'hotel']);
        $query->where([self::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS]);

        // do we have values? if so, add a filter to our query
        if (isset($params['HotelSupplierSearch'])) {
            if (!empty($params['HotelSupplierSearch']['issueDate']) && str_contains($params['HotelSupplierSearch']['issueDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['HotelSupplierSearch']['issueDate']);
                $query->andFilterWhere(['between', 'issueDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
            if (!empty($params['HotelSupplierSearch']['departureDate']) && str_contains($params['HotelSupplierSearch']['departureDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['HotelSupplierSearch']['departureDate']);
                $query->andFilterWhere(['between', 'departureDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
            if (!empty($params['HotelSupplierSearch']['refundRequestDate']) && str_contains($params['HotelSupplierSearch']['refundRequestDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['HotelSupplierSearch']['refundRequestDate']);
                $query->andFilterWhere(['between', 'refundRequestDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['issueDate' => SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['bill'] = [
            'asc' => [Bill::tableName() . '.billNumber' => SORT_ASC],
            'desc' => [Bill::tableName() . '.billNumber' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['supplier'] = [
            'asc' => [Supplier::tableName() . '.company' => SORT_ASC],
            'desc' => [Supplier::tableName() . '.company' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['hotel'] = [
            'asc' => [Hotel::tableName() . '.identificationNumber' => SORT_ASC],
            'desc' => [Hotel::tableName() . '.identificationNumber' => SORT_DESC],
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
            'hotelId' => $this->hotelId,
            'billId' => $this->billId,
            'supplierId' => $this->supplierId,
            'issueDate' => $this->issueDate,
            'refundRequestDate' => $this->refundRequestDate,
            'numberOfNights' => $this->numberOfNights,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unitPrice,
            'costOfSale' => $this->costOfSale,
            'paidAmount' => $this->paidAmount,
            'status' => $this->status,
            'motherId' => $this->motherId,
        ]);

        $query->andFilterWhere(['like', Bill::tableName() . '.billNumber', $this->bill])
            ->andFilterWhere(['like', Supplier::tableName() . '.company', $this->supplier])
            ->andFilterWhere(['like', Hotel::tableName() . '.identificationNumber', $this->hotel])
            ->andFilterWhere(['like', 'supplierRef', $this->supplierRef])
            ->andFilterWhere(['like', 'hotelName', $this->hotelName])
            ->andFilterWhere(['like', 'roomType', $this->roomType])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'serviceDetails', $this->serviceDetails])
            ->andFilterWhere(['like', 'paymentStatus', $this->paymentStatus]);

        return $dataProvider;
    }
}
