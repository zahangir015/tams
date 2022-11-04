<?php

namespace app\modules\sale\models\visa;

use app\models\Country;
use app\modules\account\models\Bill;
use app\modules\sale\models\Supplier;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sale\models\visa\VisaSupplier;

/**
 * VisaSupplierSearch represents the model behind the search form of `app\modules\sale\models\visa\VisaSupplier`.
 */
class VisaSupplierSearch extends VisaSupplier
{
    public $bill;
    public $supplier;
    public $visa;
    public $country;
    /**
     * {@inheritdoc}
     */
    public function rules():array
    {
        return [
            [['id', 'motherVisaSupplierId', 'visaId', 'billId', 'countryId', 'supplierId', 'quantity', 'status', 'motherId'], 'integer'],
            [['bill', 'supplier', 'visa', 'country', 'supplierRef', 'paxName', 'issueDate', 'refundRequestDate', 'type', 'serviceDetails', 'paymentStatus'], 'safe'],
            [['unitPrice', 'costOfSale', 'securityDeposit', 'paidAmount'], 'number'],
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
        $query = VisaSupplier::find();

        // add conditions that should always apply here
        $query->joinWith(['bill', 'supplier', 'country', 'visa']);

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

        $dataProvider->sort->attributes['visa'] = [
            'asc' => [Visa::tableName() . '.identificationNumber' => SORT_ASC],
            'desc' => [Visa::tableName() . '.identificationNumber' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['country'] = [
            'asc' => [Country::tableName() . '.name' => SORT_ASC],
            'desc' => [Country::tableName() . '.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'motherVisaSupplierId' => $this->motherVisaSupplierId,
            'visaId' => $this->visaId,
            'billId' => $this->billId,
            'countryId' => $this->countryId,
            'supplierId' => $this->supplierId,
            'issueDate' => $this->issueDate,
            'refundRequestDate' => $this->refundRequestDate,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unitPrice,
            'costOfSale' => $this->costOfSale,
            'securityDeposit' => $this->securityDeposit,
            'paidAmount' => $this->paidAmount,
            'status' => $this->status,
            'motherId' => $this->motherId,
        ]);

        $query->andFilterWhere(['like', 'supplierRef', $this->supplierRef])
            ->andFilterWhere(['like', Bill::tableName() . '.billNumber', $this->bill])
            ->andFilterWhere(['like', Supplier::tableName() . '.company', $this->supplier])
            ->andFilterWhere(['like', Country::tableName() . '.name', $this->country])
            ->andFilterWhere(['like', Visa::tableName() . '.identificationNumber', $this->visa])
            ->andFilterWhere(['like', 'paxName', $this->paxName])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'serviceDetails', $this->serviceDetails])
            ->andFilterWhere(['like', 'paymentStatus', $this->paymentStatus]);

        return $dataProvider;
    }
}
