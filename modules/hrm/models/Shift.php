<?php

namespace app\modules\hrm\models;

use Yii;

/**
 * This is the model class for table "{{%shift}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $title
 * @property string $entryTime
 * @property string $exitTime
 * @property string $totalHours
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property DepartmentShift[] $departmentShifts
 * @property EmployeeShift[] $employeeShifts
 * @property Roster[] $rosters
 */
class Shift extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shift}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'title', 'entryTime', 'exitTime', 'totalHours', 'createdBy', 'createdAt'], 'required'],
            [['entryTime', 'exitTime', 'totalHours'], 'safe'],
            [['status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['title'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['title'], 'unique'],
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
            'title' => Yii::t('app', 'Title'),
            'entryTime' => Yii::t('app', 'Entry Time'),
            'exitTime' => Yii::t('app', 'Exit Time'),
            'totalHours' => Yii::t('app', 'Total Hours'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[DepartmentShifts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartmentShifts()
    {
        return $this->hasMany(DepartmentShift::class, ['shiftId' => 'id']);
    }

    /**
     * Gets query for [[EmployeeShifts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeShifts()
    {
        return $this->hasMany(EmployeeShift::class, ['shiftId' => 'id']);
    }

    /**
     * Gets query for [[Rosters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRosters()
    {
        return $this->hasMany(Roster::class, ['shiftId' => 'id']);
    }
}
