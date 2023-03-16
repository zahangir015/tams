<?php

namespace app\modules\hrm\models;

use Yii;

/**
 * This is the model class for table "{{%payslip}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $employeeId
 * @property int $month
 * @property int $year
 * @property float $gross
 * @property float $tax
 * @property float|null $lateFine
 * @property float|null $totalAdjustment
 * @property float|null $totalDeduction
 * @property float $totalPaid
 * @property string|null $paymentMode
 * @property int|null $processStatus
 * @property string|null $remarks
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Employee $employee
 * @property PayslipTypeDetail[] $payslipTypeDetails
 */
class Payslip extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payslip}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'employeeId', 'month', 'year', 'gross', 'totalPaid', 'createdBy', 'createdAt'], 'required'],
            [['employeeId', 'month', 'year', 'processStatus', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['gross', 'tax', 'lateFine', 'totalAdjustment', 'totalDeduction', 'totalPaid'], 'number'],
            [['paymentMode'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['remarks'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['employeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['employeeId' => 'id']],
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
            'employeeId' => Yii::t('app', 'Employee ID'),
            'month' => Yii::t('app', 'Month'),
            'year' => Yii::t('app', 'Year'),
            'gross' => Yii::t('app', 'Gross'),
            'tax' => Yii::t('app', 'Tax'),
            'lateFine' => Yii::t('app', 'Late Fine'),
            'totalAdjustment' => Yii::t('app', 'Total Adjustment'),
            'totalDeduction' => Yii::t('app', 'Total Deduction'),
            'totalPaid' => Yii::t('app', 'Total Paid'),
            'paymentMode' => Yii::t('app', 'Payment Mode'),
            'processStatus' => Yii::t('app', 'Process Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['id' => 'employeeId']);
    }

    /**
     * Gets query for [[PayslipTypeDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayslipTypeDetails()
    {
        return $this->hasMany(PayslipTypeDetail::class, ['payslipId' => 'id']);
    }
}
