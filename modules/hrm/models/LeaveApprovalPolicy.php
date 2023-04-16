<?php

namespace app\modules\hrm\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%leave_approval_policy}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property int $approvalLevel
 * @property int $employeeId
 * @property int $requestedTo
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Employee $employee
 * @property Employee $requestedTo0
 */
class LeaveApprovalPolicy extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%leave_approval_policy}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['approvalLevel', 'employeeId', 'requestedTo'], 'required'],
            [['approvalLevel', 'employeeId', 'requestedTo', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['uid'], 'unique'],
            [['employeeId', 'requestedTo'], 'unique', 'on' => 'create'],
            [['employeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['employeeId' => 'id']],
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
            'uid' => Yii::t('app', 'Uid'),
            'approvalLevel' => Yii::t('app', 'Approval Level'),
            'employeeId' => Yii::t('app', 'Employee'),
            'requestedTo' => Yii::t('app', 'Requested To'),
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
     * Gets query for [[RequestedTo0]].
     *
     * @return ActiveQuery
     */
    public function getRequestedEmployee(): ActiveQuery
    {
        return $this->hasOne(Employee::class, ['id' => 'requestedTo']);
    }
}
