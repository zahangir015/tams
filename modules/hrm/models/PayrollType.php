<?php

namespace app\modules\hrm\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%payroll_type}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property int $amountType
 * @property int $calculatingMethod
 * @property float $amount
 * @property string|null $category
 * @property int $order
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 */
class PayrollType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%payroll_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'name', 'amountType', 'calculatingMethod', 'order', 'createdBy', 'createdAt'], 'required'],
            [['amountType', 'calculatingMethod', 'order', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['amount'], 'number'],
            [['category'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['order'], 'unique'],
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
            'amountType' => Yii::t('app', 'Amount Type'),
            'calculatingMethod' => Yii::t('app', 'Calculating Method'),
            'amount' => Yii::t('app', 'Amount'),
            'category' => Yii::t('app', 'Category'),
            'order' => Yii::t('app', 'Order'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }
}
