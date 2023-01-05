<?php

namespace app\modules\hrm\models;

use app\modules\admin\models\User;
use app\modules\employee\models\EmployeeEducation;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%employee}}".
 *
 * @property int $id
 * @property string $uid
 * @property int|null $userId
 * @property int|null $reportTo
 * @property string $firstName
 * @property string $lastName
 * @property string|null $fathersName
 * @property string|null $mothersName
 * @property string $dateOfBirth
 * @property int $gender
 * @property string|null $bloodGroup
 * @property int|null $maritalStatus
 * @property string|null $religion
 * @property string $nid
 * @property string $officialId
 * @property string|null $officialEmail
 * @property string|null $officialPhone
 * @property string $permanentAddress
 * @property string $presentAddress
 * @property string|null $personalEmail
 * @property string $personalPhone
 * @property string|null $contactPersonsName
 * @property string|null $contactPersonsPhone
 * @property string|null $contactPersonsAddress
 * @property string|null $contactPersonsRelation
 * @property string $joiningDate
 * @property string|null $confirmationDate
 * @property int|null $inProhibition
 * @property string $jobCategory
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property EmployeeDesignation[] $employeeDesignations
 * @property EmployeeEducation[] $employeeEducations
 * @property User $user
 */
class Employee extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%employee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['firstName', 'lastName', 'dateOfBirth', 'gender', 'nid', 'officialId', 'permanentAddress', 'presentAddress', 'personalPhone', 'joiningDate', 'jobCategory'], 'required'],
            [['userId', 'reportTo', 'gender', 'maritalStatus', 'inProhibition', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['dateOfBirth', 'joiningDate', 'confirmationDate'], 'safe'],
            [['religion'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['firstName', 'lastName', 'fathersName', 'mothersName', 'bloodGroup', 'nid', 'officialId', 'officialEmail', 'officialPhone', 'permanentAddress', 'presentAddress', 'contactPersonsName', 'contactPersonsPhone', 'contactPersonsAddress', 'contactPersonsRelation', 'jobCategory'], 'string', 'max' => 255],
            [['personalEmail', 'personalPhone'], 'string', 'max' => 100],
            [['uid'], 'unique'],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
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
            'userId' => Yii::t('app', 'User ID'),
            'reportTo' => Yii::t('app', 'Report To'),
            'firstName' => Yii::t('app', 'First Name'),
            'lastName' => Yii::t('app', 'Last Name'),
            'fathersName' => Yii::t('app', 'Fathers Name'),
            'mothersName' => Yii::t('app', 'Mothers Name'),
            'dateOfBirth' => Yii::t('app', 'Date Of Birth'),
            'gender' => Yii::t('app', 'Gender'),
            'bloodGroup' => Yii::t('app', 'Blood Group'),
            'maritalStatus' => Yii::t('app', 'Marital Status'),
            'religion' => Yii::t('app', 'Religion'),
            'nid' => Yii::t('app', 'Nid'),
            'officialId' => Yii::t('app', 'Official ID'),
            'officialEmail' => Yii::t('app', 'Official Email'),
            'officialPhone' => Yii::t('app', 'Official Phone'),
            'permanentAddress' => Yii::t('app', 'Permanent Address'),
            'presentAddress' => Yii::t('app', 'Present Address'),
            'personalEmail' => Yii::t('app', 'Personal Email'),
            'personalPhone' => Yii::t('app', 'Personal Phone'),
            'contactPersonsName' => Yii::t('app', 'Contact Persons Name'),
            'contactPersonsPhone' => Yii::t('app', 'Contact Persons Phone'),
            'contactPersonsAddress' => Yii::t('app', 'Contact Persons Address'),
            'contactPersonsRelation' => Yii::t('app', 'Contact Persons Relation'),
            'joiningDate' => Yii::t('app', 'Joining Date'),
            'confirmationDate' => Yii::t('app', 'Confirmation Date'),
            'inProhibition' => Yii::t('app', 'In Prohibition'),
            'jobCategory' => Yii::t('app', 'Job Category'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[EmployeeDesignations]].
     *
     * @return ActiveQuery
     */
    public function getEmployeeDesignations(): ActiveQuery
    {
        return $this->hasMany(EmployeeDesignation::class, ['employeeId' => 'id']);
    }

    /**
     * Gets query for [[EmployeeEducations]].
     *
     * @return ActiveQuery
     */
    public function getEmployeeEducations(): ActiveQuery
    {
        return $this->hasMany(EmployeeEducation::class, ['employeeId' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }
}
