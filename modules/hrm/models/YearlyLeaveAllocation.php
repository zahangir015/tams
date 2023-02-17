<?php

namespace app\modules\hrm\models;

use Yii;

/**
 * This is the model class for table "{{%yearly_leave_allocation}}".
 *
 * @property int $id
 * @property string $uid
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
class YearlyLeaveAllocation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%yearly_leave_allocation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'leaveTypeId', 'year', 'numberOfDays', 'createdBy', 'createdAt'], 'required'],
            [['leaveTypeId', 'year', 'numberOfDays', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['uid'], 'unique'],
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
            'leaveTypeId' => Yii::t('app', 'Leave Type ID'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveType()
    {
        return $this->hasOne(LeaveType::class, ['id' => 'leaveTypeId']);
    }
}
