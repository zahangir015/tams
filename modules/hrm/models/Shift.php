<?php

namespace app\modules\hrm\models;

use app\components\GlobalConstant;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%shift}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
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
class Shift extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shift}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'entryTime', 'exitTime', 'totalHours'], 'required'],
            [['entryTime', 'exitTime', 'totalHours'], 'safe'],
            [['status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['title'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['title'], 'unique'],
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
     * @return ActiveQuery
     */
    public function getDepartmentShifts(): ActiveQuery
    {
        return $this->hasMany(DepartmentShift::class, ['shiftId' => 'id']);
    }

    /**
     * Gets query for [[EmployeeShifts]].
     *
     * @return ActiveQuery
     */
    public function getEmployeeShifts(): ActiveQuery
    {
        return $this->hasMany(EmployeeShift::class, ['shiftId' => 'id']);
    }

    /**
     * Gets query for [[Rosters]].
     *
     * @return ActiveQuery
     */
    public function getRosters(): ActiveQuery
    {
        return $this->hasMany(Roster::class, ['shiftId' => 'id']);
    }

    public static function query(mixed $query): array
    {
        return self::find()
            ->select(['id', 'title', 'status'])
            ->where(['like', 'title', $query])
            ->andWhere([self::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([self::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId])
            ->all();
    }
}
