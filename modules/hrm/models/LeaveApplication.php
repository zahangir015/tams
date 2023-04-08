<?php

namespace app\modules\hrm\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%leave_application}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
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
class LeaveApplication extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%leave_application}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['employeeId', 'leaveTypeId', 'numberOfDays', 'from', 'to'], 'required'],
            [['employeeId', 'leaveTypeId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
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
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', 'Uid'),
            'employeeId' => Yii::t('app', 'Employee'),
            'leaveTypeId' => Yii::t('app', 'Leave Type'),
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
     * @return ActiveQuery
     */
    public function getEmployee(): ActiveQuery
    {
        return $this->hasOne(Employee::class, ['id' => 'employeeId']);
    }

    /**
     * Gets query for [[LeaveApprovalHistories]].
     *
     * @return ActiveQuery
     */
    public function getLeaveApprovalHistories(): ActiveQuery
    {
        return $this->hasMany(LeaveApprovalHistory::class, ['leaveApplicationId' => 'id']);
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
