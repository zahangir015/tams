<?php

namespace app\modules\account\models;

use app\components\GlobalConstant;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "expense_category".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property int $status
 * @property int $createdAt
 * @property int $createdBy
 * @property int|null $updatedAt
 * @property int|null $updatedBy
 *
 * @property ExpenseSubCategory[] $expenseSubCategories
 * @property Expense[] $expenses
 */
class ExpenseCategory extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'expense_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['status', 'createdAt', 'createdBy', 'updatedAt', 'updatedBy'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 150],
            [['uid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', 'Uid'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[ExpenseSubCategories]].
     *
     * @return ActiveQuery
     */
    public function getExpenseSubCategories(): ActiveQuery
    {
        return $this->hasMany(ExpenseSubCategory::class, ['categoryId' => 'id']);
    }

    /**
     * Gets query for [[Expenses]].
     *
     * @return ActiveQuery
     */
    public function getExpenses(): ActiveQuery
    {
        return $this->hasMany(Expense::class, ['categoryId' => 'id']);
    }

    public static function query(mixed $query)
    {
        return self::find()
            ->select(['id', 'name', 'status'])
            ->where(['like', 'name', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}
