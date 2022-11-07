<?php

namespace app\modules\sale\models\hotel;

use app\modules\account\models\Bill;
use app\modules\sale\models\Supplier;
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
            [['id', 'motherHotelSupplierId', 'hotelId', 'billId', 'supplierId', 'numberOfNights', 'quantity', 'status', 'motherId'], 'integer'],
            [['bill', 'supplier', 'hotel', 'supplierRef', 'issueDate', 'refundRequestDate', 'type', 'serviceDetails', 'paymentStatus'], 'safe'],
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
        $query->joinWith(['bill', 'supplier', 'hotel']);

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
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'serviceDetails', $this->serviceDetails])
            ->andFilterWhere(['like', 'paymentStatus', $this->paymentStatus]);

        return $dataProvider;
    }
}
