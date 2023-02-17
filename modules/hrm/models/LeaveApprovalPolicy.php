<?php

namespace app\modules\hrm\models;

use Yii;

/**
 * This is the model class for table "{{%leave_approval_policy}}".
 *
 * @property int $id
 * @property string $uid
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
class LeaveApprovalPolicy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%leave_approval_policy}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'approvalLevel', 'employeeId', 'requestedTo', 'createdBy', 'createdAt'], 'required'],
            [['approvalLevel', 'employeeId', 'requestedTo', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['uid'], 'unique'],
            [['employeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['employeeId' => 'id']],
            [['requestedTo'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['requestedTo' => 'id']],
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
            'approvalLevel' => Yii::t('app', 'Approval Level'),
            'employeeId' => Yii::t('app', 'Employee ID'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['id' => 'employeeId']);
    }

    /**
     * Gets query for [[RequestedTo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestedTo0()
    {
        return $this->hasOne(Employee::class, ['id' => 'requestedTo']);
    }
}
