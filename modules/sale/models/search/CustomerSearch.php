<?php

namespace app\modules\sale\models\search;

use app\components\GlobalConstant;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sale\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `app\modules\sale\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'creditModality', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid', 'name', 'company', 'customerCode', 'category', 'email', 'address', 'phone'], 'safe'],
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
        $query = Customer::find();

        // add conditions that should always apply here
        $query->where([self::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere(['agencyId' => Yii::$app->user->identity->agencyId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['company' => SORT_ASC]],
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
            'creditModality' => $this->creditModality,
            'status' => $this->status,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'updatedBy' => $this->updatedBy,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'customerCode', $this->customerCode])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}
