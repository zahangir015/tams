<?php

namespace app\modules\hrm\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%roster}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property int $departmentId
 * @property int $employeeId
 * @property int $shiftId
 * @property string $rosterDate
 * @property string $alternativeHoliday
 * @property string|null $remarks
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Department $department
 * @property Employee $employee
 * @property Shift $shift
 */
class Roster extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%roster}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['departmentId', 'employeeId', 'shiftId', 'rosterDate', 'alternativeHoliday'], 'required'],
            [['departmentId', 'employeeId', 'shiftId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['rosterDate', 'alternativeHoliday'], 'safe'],
            [['uid'], 'string', 'max' => 36],
            [['remarks'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['departmentId'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['departmentId' => 'id']],
            [['employeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['employeeId' => 'id']],
            [['shiftId'], 'exist', 'skipOnError' => true, 'targetClass' => Shift::class, 'targetAttribute' => ['shiftId' => 'id']],
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
            'departmentId' => Yii::t('app', 'Department'),
            'employeeId' => Yii::t('app', 'Employee'),
            'shiftId' => Yii::t('app', 'Shift'),
            'rosterDate' => Yii::t('app', 'Roster Date'),
            'alternativeHoliday' => Yii::t('app', 'Alternative Holiday'),
            'remarks' => Yii::t('app', 'Remarks'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Department]].
     *
     * @return ActiveQuery
     */
    public function getDepartment(): ActiveQuery
    {
        return $this->hasOne(Department::class, ['id' => 'departmentId']);
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
     * Gets query for [[Shift]].
     *
     * @return ActiveQuery
     */
    public function getShift(): ActiveQuery
    {
        return $this->hasOne(Shift::class, ['id' => 'shiftId']);
    }
}
