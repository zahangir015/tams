<?php

namespace app\modules\account\models;

use Yii;

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
class Expense extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expense';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'categoryId', 'subCategoryId', 'supplierId', 'name', 'status', 'createdAt', 'createdBy'], 'required'],
            [['categoryId', 'subCategoryId', 'supplierId', 'status', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy'], 'integer'],
            [['accruingMonth'], 'safe'],
            [['timingOfExp', 'notes'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => ExpenseCategory::class, 'targetAttribute' => ['categoryId' => 'id']],
            [['subCategoryId'], 'exist', 'skipOnError' => true, 'targetClass' => ExpenseSubCategory::class, 'targetAttribute' => ['subCategoryId' => 'id']],
            [['supplierId'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplierId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', 'Uid'),
            'categoryId' => Yii::t('app', 'Category ID'),
            'subCategoryId' => Yii::t('app', 'Sub Category ID'),
            'supplierId' => Yii::t('app', 'Supplier ID'),
            'name' => Yii::t('app', 'Name'),
            'accruingMonth' => Yii::t('app', 'Accruing Month'),
            'timingOfExp' => Yii::t('app', 'Timing Of Exp'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ExpenseCategory::class, ['id' => 'categoryId']);
    }

    /**
     * Gets query for [[SubCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubCategory()
    {
        return $this->hasOne(ExpenseSubCategory::class, ['id' => 'subCategoryId']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::class, ['id' => 'supplierId']);
    }
}
