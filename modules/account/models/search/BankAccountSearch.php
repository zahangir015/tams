<?php

namespace app\modules\account\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\account\models\BankAccount;

/**
 * BankAccountSearch represents the model behind the search form of `app\modules\account\models\BankAccount`.
 */
class BankAccountSearch extends BankAccount
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid', 'name', 'shortName', 'accountName', 'accountNumber', 'branch', 'routingNumber', 'swiftCode', 'code', 'logo', 'tag'], 'safe'],
            [['paymentCharge'], 'number'],
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
        $query = BankAccount::find();

        // add conditions that should always apply here

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
            'paymentCharge' => $this->paymentCharge,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'shortName', $this->shortName])
            ->andFilterWhere(['like', 'accountName', $this->accountName])
            ->andFilterWhere(['like', 'accountNumber', $this->accountNumber])
            ->andFilterWhere(['like', 'branch', $this->branch])
            ->andFilterWhere(['like', 'routingNumber', $this->routingNumber])
            ->andFilterWhere(['like', 'swiftCode', $this->swiftCode])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'tag', $this->tag]);

        return $dataProvider;
    }
}
