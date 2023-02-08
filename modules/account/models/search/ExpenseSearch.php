<?php

namespace app\modules\account\models\search;

use app\components\GlobalConstant;
use app\modules\account\models\Expense;
use app\modules\account\models\ExpenseCategory;
use app\modules\account\models\ExpenseSubCategory;
use app\modules\sale\models\Supplier;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ExpenseSearch represents the model behind the search form of `app\modules\account\models\Expense`.
 */
class ExpenseSearch extends Expense
{
    public $category;
    public $subCategory;
    public $supplier;
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'categoryId', 'subCategoryId', 'supplierId', 'status', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy'], 'integer'],
            [['uid', 'accruingMonth', 'timingOfExp', 'notes', 'identificationNumber', 'category', 'subCategory', 'supplier', 'totalCost', 'totalPaid', 'paymentStatus'], 'safe'],
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
        $query = Expense::find();

        // add conditions that should always apply here
        $query->joinWith(['category', 'subCategory', 'supplier'])->where([self::tableName().'.status' => GlobalConstant::ACTIVE_STATUS]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['category'] = [
            'asc' => [ExpenseCategory::tableName() . '.name' => SORT_ASC],
            'desc' => [ExpenseCategory::tableName() . '.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['subCategory'] = [
            'asc' => [ExpenseSubCategory::tableName() . '.name' => SORT_ASC],
            'desc' => [ExpenseSubCategory::tableName() . '.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['supplier'] = [
            'asc' => [Supplier::tableName() . '.name' => SORT_ASC],
            'desc' => [Supplier::tableName() . '.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            self::tableName().'.identificationNumber' => $this->identificationNumber,
            self::tableName().'.accruingMonth' => $this->accruingMonth,
            self::tableName().'.totalCost' => $this->totalCost,
            self::tableName().'.totalPaid' => $this->totalPaid,
            self::tableName().'.paymentStatus' => $this->paymentStatus,
            self::tableName().'.status' => $this->status,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
        ]);

        $query->andFilterWhere(['like', ExpenseCategory::tableName() . '.name', $this->category])
            ->andFilterWhere(['like', ExpenseSubCategory::tableName() . '.name', $this->subCategory])
            ->andFilterWhere(['like', Supplier::tableName() . '.name', $this->supplier])
            ->orFilterWhere(['like', Supplier::tableName() . '.company', $this->supplier])
            ->andFilterWhere(['like', self::tableName().'.timingOfExp', $this->timingOfExp])
            ->andFilterWhere(['like', 'notes', $this->notes]);
        return $dataProvider;
    }
}
