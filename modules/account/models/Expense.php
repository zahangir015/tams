<?php

namespace app\modules\account\models;

use app\modules\sale\models\Supplier;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "expense".
 *
 * @property int $id
 * @property string $uid
 * @property int $categoryId
 * @property int $subCategoryId
 * @property int $supplierId
 * @property string $name
 * @property string|null $accruingMonth
 * @property string|null $timingOfExp
 * @property double $totalCost
 * @property double $totalPaid
 * @property string $paymentStatus
 * @property string|null $notes
 * @property int $status
 * @property int $createdAt
 * @property int|null $updatedAt
 * @property int $createdBy
 * @property int|null $updatedBy
 *
 * @property ExpenseCategory $category
 * @property ExpenseSubCategory $subCategory
 * @property Supplier $supplier
 */
class Expense extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'expense';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['categoryId', 'subCategoryId'], 'required'],
            [['categoryId', 'subCategoryId', 'supplierId', 'status', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy'], 'integer'],
            [['accruingMonth'], 'safe'],
            [['totalCost', 'totalPaid'], 'number'],
            [['timingOfExp', 'notes', 'paymentStatus'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['uid'], 'unique'],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => ExpenseCategory::class, 'targetAttribute' => ['categoryId' => 'id']],
            [['subCategoryId'], 'exist', 'skipOnError' => true, 'targetClass' => ExpenseSubCategory::class, 'targetAttribute' => ['subCategoryId' => 'id']],
            [['supplierId'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplierId' => 'id']],
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
            'subCategoryId' => Yii::t('app', 'Sub Category'),
            'supplierId' => Yii::t('app', 'Supplier'),
            'accruingMonth' => Yii::t('app', 'Accruing Month'),
            'timingOfExp' => Yii::t('app', 'Timing Of Exp'),
            'totalCost' => Yii::t('app', 'Total Cost'),
            'totalPaid' => Yii::t('app', 'Total Paid'),
            'paymentStatus' => Yii::t('app', 'Payment Status'),
            'notes' => Yii::t('app', 'Notes'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdBy' => Yii::t('app', 'Created By'),
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
     * Gets query for [[SubCategory]].
     *
     * @return ActiveQuery
     */
    public function getSubCategory(): ActiveQuery
    {
        return $this->hasOne(ExpenseSubCategory::class, ['id' => 'subCategoryId']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return ActiveQuery
     */
    public function getSupplier(): ActiveQuery
    {
        return $this->hasOne(Supplier::class, ['id' => 'supplierId']);
    }
}
