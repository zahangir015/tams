<?php

namespace app\modules\account\models\search;

use app\components\GlobalConstant;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\account\models\Bill;

/**
 * BillSearch represents the model behind the search form of `app\modules\account\models\Bill`.
 */
class BillSearch extends Bill
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'supplierId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid', 'billNumber', 'date', 'remarks'], 'safe'],
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
        $query = Bill::find();

        // add conditions that should always apply here
        $query->where([self::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere(['agencyId' => Yii::$app->user->identity->agencyId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'supplierId' => $this->supplierId,
            'date' => $this->date,
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
            ->andFilterWhere(['like', 'billNumber', $this->billNumber])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }
}
