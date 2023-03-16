<?php

namespace app\modules\hrm\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%employee_payroll_type_detail}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $employeePayrollId
 * @property int $payrollTypeId
 * @property float $amount
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property EmployeePayroll $employeePayroll
 * @property PayrollType $payrollType
 */
class EmployeePayrollTypeDetail extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%employee_payroll_type_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['employeePayrollId', 'payrollTypeId', 'amount'], 'required'],
            [['employeePayrollId', 'payrollTypeId', 'status'], 'integer'],
            [['employeePayrollId', 'payrollTypeId'], 'unique'],
            [['amount'], 'number'],
            [['employeePayrollId'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeePayroll::class, 'targetAttribute' => ['employeePayrollId' => 'id']],
            [['payrollTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => PayrollType::class, 'targetAttribute' => ['payrollTypeId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'employeePayrollId' => Yii::t('app', 'Employee Payroll'),
            'payrollTypeId' => Yii::t('app', 'Payroll Type'),
            'amount' => Yii::t('app', 'Amount'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[EmployeePayroll]].
     *
     * @return ActiveQuery
     */
    public function getEmployeePayroll(): ActiveQuery
    {
        return $this->hasOne(EmployeePayroll::class, ['id' => 'employeePayrollId']);
    }

    /**
     * Gets query for [[PayrollType]].
     *
     * @return ActiveQuery
     */
    public function getPayrollType(): ActiveQuery
    {
        return $this->hasOne(PayrollType::class, ['id' => 'payrollTypeId']);
    }
}
