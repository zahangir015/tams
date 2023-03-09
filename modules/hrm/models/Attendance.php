<?php

namespace app\modules\hrm\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%attendance}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $employeeId
 * @property int $shiftId
 * @property int|null $leaveTypeId
 * @property int|null $leaveApplicationId
 * @property int $rosterId
 * @property string $date
 * @property string $entry
 * @property string|null $exit
 * @property int|null $isAbsent
 * @property int|null $isLate
 * @property int|null $isEarlyOut
 * @property string|null $totalLateInTime
 * @property string|null $totalEarlyOutTime
 * @property string|null $totalWorkingHours
 * @property string|null $overTime
 * @property string|null $remarks
 * @property string|null $employeeNote
 * @property int|null $status
 * @property int $createdBy
 * @property int|null $updatedBy
 * @property int $createdAt
 * @property int|null $updatedAt
 *
 * @property Employee $employee
 * @property LeaveApplication $leaveApplication
 * @property LeaveType $leaveType
 * @property Roster $roster
 * @property Shift $shift
 */
class Attendance extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%attendance}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['employeeId', 'shiftId', 'date'], 'required'],
            [['employeeId', 'shiftId', 'date', 'entry'], 'required', 'on' => ['create']],
            [['employeeId', 'shiftId', 'leaveTypeId', 'leaveApplicationId', 'rosterId', 'isAbsent', 'isLate', 'isEarlyOut', 'status', 'createdBy', 'updatedBy', 'createdAt', 'updatedAt'], 'integer'],
            [['date', 'entry', 'exit', 'totalLateInTime', 'totalEarlyOutTime', 'totalWorkingHours', 'overTime'], 'safe'],
            [['uid'], 'string', 'max' => 36],
            [['remarks', 'employeeNote'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['employeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['employeeId' => 'id']],
            [['leaveApplicationId'], 'exist', 'skipOnError' => true, 'targetClass' => LeaveApplication::class, 'targetAttribute' => ['leaveApplicationId' => 'id']],
            [['leaveTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => LeaveType::class, 'targetAttribute' => ['leaveTypeId' => 'id']],
            [['rosterId'], 'exist', 'skipOnError' => true, 'targetClass' => Roster::class, 'targetAttribute' => ['rosterId' => 'id']],
            [['shiftId'], 'exist', 'skipOnError' => true, 'targetClass' => Shift::class, 'targetAttribute' => ['shiftId' => 'id']],
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
            'employeeId' => Yii::t('app', 'Employee'),
            'shiftId' => Yii::t('app', 'Shift ID'),
            'leaveTypeId' => Yii::t('app', 'Leave Type ID'),
            'leaveApplicationId' => Yii::t('app', 'Leave Application ID'),
            'rosterId' => Yii::t('app', 'Roster ID'),
            'date' => Yii::t('app', 'Date'),
            'entry' => Yii::t('app', 'Entry'),
            'exit' => Yii::t('app', 'Exit'),
            'isAbsent' => Yii::t('app', 'Is Absent'),
            'isLate' => Yii::t('app', 'Is Late'),
            'isEarlyOut' => Yii::t('app', 'Is EarlyOut'),
            'totalLateInTime' => Yii::t('app', 'LateIn'),
            'totalEarlyOutTime' => Yii::t('app', 'EarlyOut'),
            'totalWorkingHours' => Yii::t('app', 'WorkingHours'),
            'overTime' => Yii::t('app', 'Over Time'),
            'remarks' => Yii::t('app', 'Remarks'),
            'employeeNote' => Yii::t('app', 'Notes'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'createdAt' => Yii::t('app', 'Created At'),
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
     * Gets query for [[LeaveApplication]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveApplication()
    {
        return $this->hasOne(LeaveApplication::class, ['id' => 'leaveApplicationId']);
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

    /**
     * Gets query for [[Roster]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoster()
    {
        return $this->hasOne(Roster::class, ['id' => 'rosterId']);
    }

    /**
     * Gets query for [[Shift]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShift()
    {
        return $this->hasOne(Shift::class, ['id' => 'shiftId']);
    }
}
