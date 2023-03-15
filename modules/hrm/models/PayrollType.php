<?php

namespace app\modules\hrm\models;

use Yii;

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
 * @property EmployeePayrollTypeDetail[] $employeePayrollTypeDetails
 * @property PayslipTypeDetail[] $payslipTypeDetails
 */
class PayrollType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payroll_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
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

    /**
     * Gets query for [[EmployeePayrollTypeDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeePayrollTypeDetails()
    {
        return $this->hasMany(EmployeePayrollTypeDetail::class, ['payrollTypeId' => 'id']);
    }

    /**
     * Gets query for [[PayslipTypeDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayslipTypeDetails()
    {
        return $this->hasMany(PayslipTypeDetail::class, ['payrollTypeId' => 'id']);
    }
}
