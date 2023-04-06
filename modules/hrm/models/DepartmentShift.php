<?php

namespace app\modules\hrm\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%department_shift}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property int $departmentId
 * @property int $shiftId
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Department $department
 * @property Shift $shift
 */
class DepartmentShift extends ActiveRecord
{
    use  BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%department_shift}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['departmentId', 'shiftId'], 'required'],
            [['departmentId', 'shiftId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['departmentId', 'shiftId'], 'unique'],
            [['uid'], 'unique'],
            [['departmentId'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['departmentId' => 'id']],
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
            'shiftId' => Yii::t('app', 'Shift'),
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
     * Gets query for [[Shift]].
     *
     * @return ActiveQuery
     */
    public function getShift(): ActiveQuery
    {
        return $this->hasOne(Shift::class, ['id' => 'shiftId']);
    }
}
