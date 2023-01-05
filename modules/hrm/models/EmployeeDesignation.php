<?php

namespace app\modules\hrm\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%employee_designation}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $employeeId
 * @property int $departmentId
 * @property int $designationId
 * @property int $branchId
 * @property string $startDate
 * @property string $endDate
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Branch $branch
 * @property Department $department
 * @property Designation $designation
 * @property Employee $employee
 */
class EmployeeDesignation extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%employee_designation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['employeeId', 'departmentId', 'designationId', 'branchId', 'startDate'], 'required'],
            [['employeeId', 'departmentId', 'designationId', 'branchId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['startDate', 'endDate'], 'safe'],
            [['uid'], 'string', 'max' => 36],
            [['uid'], 'unique'],
            [['branchId'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::class, 'targetAttribute' => ['branchId' => 'id']],
            [['departmentId'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['departmentId' => 'id']],
            [['designationId'], 'exist', 'skipOnError' => true, 'targetClass' => Designation::class, 'targetAttribute' => ['designationId' => 'id']],
            [['employeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['employeeId' => 'id']],
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
            'employeeId' => Yii::t('app', 'Employee'),
            'departmentId' => Yii::t('app', 'Department'),
            'designationId' => Yii::t('app', 'Designation'),
            'branchId' => Yii::t('app', 'Branch'),
            'startDate' => Yii::t('app', 'Start Date'),
            'endDate' => Yii::t('app', 'End Date'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Branch]].
     *
     * @return ActiveQuery
     */
    public function getBranch(): ActiveQuery
    {
        return $this->hasOne(Branch::class, ['id' => 'branchId']);
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
     * Gets query for [[Designation]].
     *
     * @return ActiveQuery
     */
    public function getDesignation(): ActiveQuery
    {
        return $this->hasOne(Designation::class, ['id' => 'designationId']);
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
}
