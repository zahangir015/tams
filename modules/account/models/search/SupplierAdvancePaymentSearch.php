<?php

namespace app\modules\account\models\search;

use app\components\GlobalConstant;
use app\modules\sale\models\Supplier;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\account\models\AdvancePayment;

/**
 * AdvancePaymentSearch represents the model behind the search form of `app\modules\account\models\AdvancePayment`.
 */
class SupplierAdvancePaymentSearch extends AdvancePayment
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'refId', 'bankId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'refModel', 'date', 'remarks'], 'safe'],
            [['paidAmount'], 'number'],
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
        $query = AdvancePayment::find();

        // add conditions that should always apply here
        $query->where([self::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([self::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
            ->andWhere([self::tableName().'.refModel' => Supplier::class]);

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
            'refId' => $this->refId,
            'bankId' => $this->bankId,
            'date' => $this->date,
            'paidAmount' => $this->paidAmount,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'refModel', $this->refModel])
            ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }
}
