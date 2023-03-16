<?php

namespace app\modules\hrm\models;

use Yii;

/**
 * This is the model class for table "{{%payslip_type_detail}}".
 *
 * @property int $id
 * @property int $payrollTypeId
 * @property int $payslipId
 * @property float $amount
 * @property float $calculatedAmount
 *
 * @property PayrollType $payrollType
 * @property Payslip $payslip
 */
class PayslipTypeDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payslip_type_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payrollTypeId', 'payslipId'], 'required'],
            [['payrollTypeId', 'payslipId'], 'integer'],
            [['amount', 'calculatedAmount'], 'number'],
            [['payrollTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => PayrollType::class, 'targetAttribute' => ['payrollTypeId' => 'id']],
            [['payslipId'], 'exist', 'skipOnError' => true, 'targetClass' => Payslip::class, 'targetAttribute' => ['payslipId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'payrollTypeId' => Yii::t('app', 'Payroll Type ID'),
            'payslipId' => Yii::t('app', 'Payslip ID'),
            'amount' => Yii::t('app', 'Amount'),
            'calculatedAmount' => Yii::t('app', 'Calculated Amount'),
        ];
    }

    /**
     * Gets query for [[PayrollType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayrollType()
    {
        return $this->hasOne(PayrollType::class, ['id' => 'payrollTypeId']);
    }

    /**
     * Gets query for [[Payslip]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayslip()
    {
        return $this->hasOne(Payslip::class, ['id' => 'payslipId']);
    }
}
