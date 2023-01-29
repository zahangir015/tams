<?php

namespace app\modules\sale\models\ticket;

use app\modules\account\models\Bill;
use app\modules\sale\models\Airline;
use app\modules\sale\models\Supplier;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TicketSupplierSearch represents the model behind the search form of `app\modules\sale\models\ticket\TicketSupplier`.
 */
class TicketSupplierSearch extends TicketSupplier
{
    public $bill;
    public $supplier;
    public $ticket;
    public $airline;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'ticketId', 'supplierId', 'airlineId', 'billId', 'status', 'motherId'], 'integer'],
            [['bill', 'supplier', 'ticket', 'airline', 'issueDate', 'refundRequestDate', 'eTicket', 'pnrCode', 'type', 'paymentStatus'], 'safe'],
            [['baseFare', 'tax', 'otherTax', 'costOfSale', 'paidAmount', 'serviceCharge'], 'number'],
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
        $query = TicketSupplier::find();

        // add conditions that should always apply here
        $query->joinWith(['bill', 'supplier', 'ticket', 'airline']);

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
            'asc' => [Ticket::tableName() . '.eTicket' => SORT_ASC],
            'desc' => [Ticket::tableName() . '.eTicket' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['airline'] = [
            'asc' => [Airline::tableName() . '.name' => SORT_ASC],
            'desc' => [Airline::tableName() . '.name' => SORT_DESC],
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
            'ticketId' => $this->ticketId,
            'supplierId' => $this->supplierId,
            'airlineId' => $this->airlineId,
            'billId' => $this->billId,
            'issueDate' => $this->issueDate,
            'refundRequestDate' => $this->refundRequestDate,
            'baseFare' => $this->baseFare,
            'tax' => $this->tax,
            'otherTax' => $this->otherTax,
            'costOfSale' => $this->costOfSale,
            'paidAmount' => $this->paidAmount,
            'status' => $this->status,
            'serviceCharge' => $this->serviceCharge,
            'motherId' => $this->motherId,
        ]);

        $query->andFilterWhere(['like', Bill::tableName() . '.billNumber', $this->bill])
            ->andFilterWhere(['like', Supplier::tableName() . '.company', $this->supplier])
            ->andFilterWhere(['like', Ticket::tableName() . '.eTicket', $this->ticket])
            ->andFilterWhere(['like', Airline::tableName() . '.name', $this->airline])
            ->andFilterWhere(['like', 'eTicket', $this->eTicket])
            ->andFilterWhere(['like', 'pnrCode', $this->pnrCode])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'paymentStatus', $this->paymentStatus]);

        return $dataProvider;
    }
}
