<?php

namespace app\modules\hrm\models;

use Yii;

/**
 * This is the model class for table "{{%roster}}".
 *
 * @property int $id
 * @property string $uid
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
class Roster extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%roster}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'departmentId', 'employeeId', 'shiftId', 'rosterDate', 'alternativeHoliday', 'createdBy', 'createdAt'], 'required'],
            [['departmentId', 'employeeId', 'shiftId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', 'Uid'),
            'departmentId' => Yii::t('app', 'Department ID'),
            'employeeId' => Yii::t('app', 'Employee ID'),
            'shiftId' => Yii::t('app', 'Shift ID'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'departmentId']);
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
     * Gets query for [[Shift]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShift()
    {
        return $this->hasOne(Shift::class, ['id' => 'shiftId']);
    }
}
