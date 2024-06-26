<?php

namespace app\modules\account\models\search;
use app\components\GlobalConstant;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\account\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form of `app\modules\account\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'customerId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid', 'invoiceNumber', 'date', 'expectedPaymentDate', 'remarks'], 'safe'],
            [['paidAmount', 'dueAmount', 'discountedAmount', 'refundAdjustmentAmount'], 'number'],
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
        $query = Invoice::find();

        // add conditions that should always apply here
        $query->where([self::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([self::tableName().'.agencyId' => Yii::$app->user->identity->agencyId]);

        if (isset($params['InvoiceSearch'])) {
            if (!empty($params['InvoiceSearch']['date']) && str_contains($params['InvoiceSearch']['date'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['InvoiceSearch']['date']);
                $query->andFilterWhere(['between', 'date', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
            if (!empty($params['InvoiceSearch']['expectedPaymentDate']) && str_contains($params['InvoiceSearch']['expectedPaymentDate'], '-')) {
                list($start_date, $end_date) = explode(' - ', $params['InvoiceSearch']['expectedPaymentDate']);
                $query->andFilterWhere(['between', 'expectedPaymentDate', date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'customerId' => $this->customerId,
            'date' => $this->date,
            'expectedPaymentDate' => $this->expectedPaymentDate,
            'paidAmount' => $this->paidAmount,
            'dueAmount' => $this->dueAmount,
            'discountedAmount' => $this->discountedAmount,
            'refundAdjustmentAmount' => $this->refundAdjustmentAmount,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'invoiceNumber', $this->invoiceNumber])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }
}
