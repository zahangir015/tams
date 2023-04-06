<?php

namespace app\modules\hrm\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%employee_payroll}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property int $employeeId
 * @property float $gross
 * @property float $tax
 * @property string|null $paymentMode
 * @property string|null $remarks
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Employee $employee
 * @property EmployeePayrollTypeDetail[] $employeePayrollTypeDetails
 */
class EmployeePayroll extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%employee_payroll}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['employeeId', 'gross', 'tax'], 'required'],
            [['employeeId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['gross', 'tax'], 'number'],
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
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', 'Uid'),
            'employeeId' => Yii::t('app', 'Employee'),
            'gross' => Yii::t('app', 'Gross'),
            'tax' => Yii::t('app', 'Tax'),
            'paymentMode' => Yii::t('app', 'Payment Mode'),
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
     * @return ActiveQuery
     */
    public function getEmployee(): ActiveQuery
    {
        return $this->hasOne(Employee::class, ['id' => 'employeeId']);
    }

    /**
     * Gets query for [[EmployeePayrollTypeDetails]].
     *
     * @return ActiveQuery
     */
    public function getEmployeePayrollTypeDetails(): ActiveQuery
    {
        return $this->hasMany(EmployeePayrollTypeDetail::class, ['employeePayrollId' => 'id']);
    }
}
