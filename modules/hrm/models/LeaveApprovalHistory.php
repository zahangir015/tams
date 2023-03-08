<?php

namespace app\modules\hrm\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%leave_approval_history}}".
 *
 * @property int $id
 * @property int $leaveApplicationId
 * @property int $requestedTo
 * @property int $approvalLevel
 * @property string|null $approvalStatus
 * @property string|null $remarks
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property LeaveApplication $leaveApplication
 * @property Employee $requestedTo0
 */
class LeaveApprovalHistory extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%leave_approval_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['leaveApplicationId', 'requestedTo', 'approvalLevel'], 'required'],
            [['leaveApplicationId', 'requestedTo', 'approvalLevel', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['approvalStatus'], 'string'],
            [['remarks'], 'string', 'max' => 255],
            [['leaveApplicationId'], 'exist', 'skipOnError' => true, 'targetClass' => LeaveApplication::class, 'targetAttribute' => ['leaveApplicationId' => 'id']],
            [['requestedTo'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['requestedTo' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'leaveApplicationId' => Yii::t('app', 'Leave Application ID'),
            'requestedTo' => Yii::t('app', 'Requested To'),
            'approvalLevel' => Yii::t('app', 'Approval Level'),
            'approvalStatus' => Yii::t('app', 'Approval Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[LeaveApplication]].
     *
     * @return ActiveQuery
     */
    public function getLeaveApplication(): ActiveQuery
    {
        return $this->hasOne(LeaveApplication::class, ['id' => 'leaveApplicationId']);
    }

    /**
     * Gets query for [[RequestedTo0]].
     *
     * @return ActiveQuery
     */
    public function getRequested(): ActiveQuery
    {
        return $this->hasOne(Employee::class, ['id' => 'requestedTo']);
    }
}
