<?php

namespace app\modules\account\models;

use app\components\GlobalConstant;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "expense_sub_category".
 *
 * @property int $id
 * @property string $uid
 * @property int $categoryId
 * @property string $name
 * @property int $status
 * @property int $createdAt
 * @property int $createdBy
 * @property int|null $updatedAt
 * @property int|null $updatedBy
 *
 * @property ExpenseCategory $category
 * @property Expense[] $expenses
 */
class ExpenseSubCategory extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'expense_sub_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['categoryId', 'name'], 'required'],
            [['categoryId', 'status', 'createdAt', 'createdBy', 'updatedAt', 'updatedBy'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => ExpenseCategory::class, 'targetAttribute' => ['categoryId' => 'id']],
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
            'categoryId' => Yii::t('app', 'Category'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(ExpenseCategory::class, ['id' => 'categoryId']);
    }

    /**
     * Gets query for [[Expenses]].
     *
     * @return ActiveQuery
     */
    public function getExpenses(): ActiveQuery
    {
        return $this->hasMany(Expense::class, ['subCategoryId' => 'id']);
    }

    public static function query(mixed $query): array
    {
        return self::find()
            ->select(['id', 'name', 'status'])
            ->where(['like', 'name', $query])
            ->andWhere([self::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([self::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
            ->all();
    }
}
