<?php

namespace app\modules\account\models\search;

use app\components\GlobalConstant;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\account\models\Ledger;

/**
 * LedgerSearch represents the model behind the search form of `app\modules\account\models\Ledger`.
 */
class LedgerSearch extends Ledger
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'refId', 'subRefId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid', 'title', 'date', 'reference', 'refModel', 'subRefModel'], 'safe'],
            [['debit', 'credit', 'balance'], 'number'],
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
        $query = Ledger::find();

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
            'date' => $this->date,
            'refId' => $this->refId,
            'subRefId' => $this->subRefId,
            'debit' => $this->debit,
            'credit' => $this->credit,
            'balance' => $this->balance,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'refModel', $this->refModel])
            ->andFilterWhere(['like', 'subRefModel', $this->subRefModel]);

        return $dataProvider;
    }
}
