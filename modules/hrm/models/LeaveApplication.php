<?php

namespace app\modules\hrm\models;

use Yii;

/**
 * This is the model class for table "{{%leave_application}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $employeeId
 * @property int $leaveTypeId
 * @property float $numberOfDays
 * @property string $from
 * @property string $to
 * @property string|null $availableFrom
 * @property string|null $description
 * @property string|null $remarks
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Employee $employee
 * @property LeaveApprovalHistory[] $leaveApprovalHistories
 * @property LeaveType $leaveType
 */
class LeaveApplication extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%leave_application}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'employeeId', 'leaveTypeId', 'numberOfDays', 'from', 'to', 'createdBy', 'createdAt'], 'required'],
            [['employeeId', 'leaveTypeId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['numberOfDays'], 'number'],
            [['from', 'to', 'availableFrom'], 'safe'],
            [['description'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['remarks'], 'string', 'max' => 255],
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
            'numberOfDays' => Yii::t('app', 'Number Of Days'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'availableFrom' => Yii::t('app', 'Available From'),
            'description' => Yii::t('app', 'Description'),
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
     * Gets query for [[LeaveApprovalHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveApprovalHistories()
    {
        return $this->hasMany(LeaveApprovalHistory::class, ['leaveApplicationId' => 'id']);
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
