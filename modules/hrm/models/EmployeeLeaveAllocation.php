<?php

namespace app\modules\hrm\models;

use Yii;

/**
 * This is the model class for table "{{%employee_leave_allocation}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $employeeId
 * @property int $leaveTypeId
 * @property int $year
 * @property int $totalDays
 * @property int $availedDays
 * @property int $remainingDays
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Employee $employee
 * @property LeaveType $leaveType
 */
class EmployeeLeaveAllocation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%employee_leave_allocation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'employeeId', 'leaveTypeId', 'year', 'totalDays', 'createdBy', 'createdAt'], 'required'],
            [['employeeId', 'leaveTypeId', 'year', 'totalDays', 'availedDays', 'remainingDays', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['uid'], 'unique'],
            [['employeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['employeeId' => 'id']],
            [['leaveTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => LeaveType::class, 'targetAttribute' => ['leaveTypeId' => 'id']],
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
            'leaveTypeId' => Yii::t('app', 'Leave Type ID'),
            'year' => Yii::t('app', 'Year'),
            'totalDays' => Yii::t('app', 'Total Days'),
            'availedDays' => Yii::t('app', 'Availed Days'),
            'remainingDays' => Yii::t('app', 'Remaining Days'),
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
     * Gets query for [[LeaveType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveType()
    {
        return $this->hasOne(LeaveType::class, ['id' => 'leaveTypeId']);
    }
}
