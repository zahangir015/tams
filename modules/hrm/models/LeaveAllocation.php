<?php

namespace app\modules\hrm\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
class LeaveAllocation extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%employee_leave_allocation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['employeeId', 'leaveTypeId', 'year', 'totalDays'], 'required'],
            [['employeeId', 'leaveTypeId', 'year', 'totalDays', 'availedDays', 'remainingDays', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['uid'], 'unique'],
            [['uid'], 'unique'],
            [['employeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['employeeId' => 'id']],
            [['leaveTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => LeaveType::class, 'targetAttribute' => ['leaveTypeId' => 'id']],
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
            'leaveTypeId' => Yii::t('app', 'Leave Type'),
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
     * @return ActiveQuery
     */
    public function getEmployee(): ActiveQuery
    {
        return $this->hasOne(Employee::class, ['id' => 'employeeId']);
    }

    /**
     * Gets query for [[LeaveType]].
     *
     * @return ActiveQuery
     */
    public function getLeaveType(): ActiveQuery
    {
        return $this->hasOne(LeaveType::class, ['id' => 'leaveTypeId']);
    }
}
