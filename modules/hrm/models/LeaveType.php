<?php

namespace app\modules\hrm\models;

use app\components\GlobalConstant;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%leave_type}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property int $defaultDays
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property EmployeeLeaveAllocation[] $employeeLeaveAllocations
 * @property LeaveApplication[] $leaveApplications
 * @property YearlyLeaveAllocation[] $yearlyLeaveAllocations
 */
class LeaveType extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%leave_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'defaultDays'], 'required'],
            [['defaultDays', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['name'], 'unique'],
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
            'name' => Yii::t('app', 'Name'),
            'defaultDays' => Yii::t('app', 'Default Days'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    public static function query(mixed $query): array
    {
        return self::find()
            ->select(['id', 'name', 'status'])
            ->where(['like', 'name', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}
