<?php

namespace app\modules\sale\models\holiday;

use app\components\GlobalConstant;
use app\modules\account\models\Bill;
use app\modules\sale\models\Supplier;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * HolidaySupplierSearch represents the model behind the search form of `app\modules\sale\models\holiday\HolidaySupplier`.
 */
class HolidaySupplierSearch extends HolidaySupplier
{
    public $bill;
    public $supplier;
    public $holiday;
    public $category;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'holidayId', 'billId', 'supplierId', 'quantity', 'unitPrice', 'status', 'holidayCategoryId', 'motherId'], 'integer'],
            [['uid', 'supplierRef', 'issueDate', 'departureDate', 'refundRequestDate', 'type', 'serviceDetails', 'paymentStatus', 'holiday', 'supplier', 'category', 'bill', 'title'], 'safe'],
            [['costOfSale', 'paidAmount'], 'number'],
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
        $query = HolidaySupplier::find();

        // add conditions that should always apply here
        $query->joinWith(['holiday', 'supplier'=> function ($query) {
            $query->where([Supplier::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId]);
        }, 'category', 'bill']);
        $query->where([self::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS]);

        // do we have values? if so, add a filter to our query
        if (isset($params['HolidaySupplierSearch'])) {
            if (!empty($params['HolidaySupplierSearch']['issueDate']) && str_contains($params['HolidaySupplierSearch']['issueDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['HolidaySupplierSearch']['issueDate']);
                $query->andFilterWhere(['between', 'issueDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
            if (!empty($params['HolidaySupplierSearch']['departureDate']) && str_contains($params['HolidaySupplierSearch']['departureDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['HolidaySupplierSearch']['departureDate']);
                $query->andFilterWhere(['between', 'departureDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
            if (!empty($params['HolidaySupplierSearch']['refundRequestDate']) && str_contains($params['HolidaySupplierSearch']['refundRequestDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['HolidaySupplierSearch']['refundRequestDate']);
                $query->andFilterWhere(['between', 'refundRequestDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['issueDate' => SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['category'] = [
            'asc' => [HolidayCategory::tableName() . '.name' => SORT_ASC],
            'desc' => [HolidayCategory::tableName() . '.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['bill'] = [
            'asc' => [Bill::tableName() . '.billNumber' => SORT_ASC],
            'desc' => [Bill::tableName() . '.billNumber' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['supplier'] = [
            'asc' => [Supplier::tableName() . '.company' => SORT_ASC],
            'desc' => [Supplier::tableName() . '.company' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['holiday'] = [
            'asc' => [Holiday::tableName() . '.identificationNumber' => SORT_ASC],
            'desc' => [Holiday::tableName() . '.identificationNumber' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'holidayId' => $this->holidayId,
            'billId' => $this->billId,
            'supplierId' => $this->supplierId,
            'issueDate' => $this->issueDate,
            'departureDate' => $this->departureDate,
            'refundRequestDate' => $this->refundRequestDate,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unitPrice,
            'costOfSale' => $this->costOfSale,
            'paidAmount' => $this->paidAmount,
            'status' => $this->status,
            'holidayCategoryId' => $this->holidayCategoryId,
            'motherId' => $this->motherId,
        ]);

        $query->andFilterWhere(['like', Bill::tableName() . '.billNumber', $this->bill])
            ->andFilterWhere(['like', Supplier::tableName() . '.company', $this->supplier])
            ->andFilterWhere(['like', Holiday::tableName() . '.name', $this->holiday])
            ->andFilterWhere(['like', HolidayCategory::tableName() . '.name', $this->category])
            ->andFilterWhere(['like', 'supplierRef', $this->supplierRef])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'serviceDetails', $this->serviceDetails])
            ->andFilterWhere(['like', 'paymentStatus', $this->paymentStatus]);

        return $dataProvider;
    }
}
