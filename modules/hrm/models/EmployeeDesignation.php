<?php

namespace app\modules\hrm\models;

use Yii;

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
class EmployeeDesignation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%employee_designation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'employeeId', 'departmentId', 'designationId', 'branchId', 'startDate', 'endDate', 'createdBy', 'createdAt'], 'required'],
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', 'Uid'),
            'employeeId' => Yii::t('app', 'Employee ID'),
            'departmentId' => Yii::t('app', 'Department ID'),
            'designationId' => Yii::t('app', 'Designation ID'),
            'branchId' => Yii::t('app', 'Branch ID'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::class, ['id' => 'branchId']);
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
     * Gets query for [[Designation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDesignation()
    {
        return $this->hasOne(Designation::class, ['id' => 'designationId']);
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
}
