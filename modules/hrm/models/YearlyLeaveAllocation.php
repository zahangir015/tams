<?php

namespace app\modules\hrm\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%yearly_leave_allocation}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property int $leaveTypeId
 * @property int $year
 * @property int $numberOfDays
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property LeaveType $leaveType
 */
class YearlyLeaveAllocation extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%yearly_leave_allocation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['leaveTypeId', 'year', 'numberOfDays'], 'required'],
            [['leaveTypeId', 'year', 'numberOfDays', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['uid'], 'unique'],
            [['leaveTypeId', 'year'], 'unique', 'on' => ['create']],
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
            'leaveTypeId' => Yii::t('app', 'Leave Type'),
            'year' => Yii::t('app', 'Year'),
            'numberOfDays' => Yii::t('app', 'Number Of Days'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
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
