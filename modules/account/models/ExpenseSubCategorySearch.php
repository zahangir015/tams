<?php

namespace app\modules\account\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\account\models\ExpenseSubCategory;

/**
 * ExpenseSubCategorySearch represents the model behind the search form of `app\modules\account\models\ExpenseSubCategory`.
 */
class ExpenseSubCategorySearch extends ExpenseSubCategory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'categoryId', 'status', 'createdAt', 'createdBy', 'updatedAt', 'updatedBy'], 'integer'],
            [['uid', 'name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
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
    public function search($params)
    {
        $query = ExpenseSubCategory::find();

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
            'categoryId' => $this->categoryId,
            'status' => $this->status,
            'createdAt' => $this->createdAt,
            'createdBy' => $this->createdBy,
            'updatedAt' => $this->updatedAt,
            'updatedBy' => $this->updatedBy,
        ]);

        $query->andFilterWhere(['like', 'uid', $this->uid])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
