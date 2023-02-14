<?php

namespace app\modules\hrm\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%weekend}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $departmentId
 * @property string $day
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Department $department
 */
class Weekend extends \yii\db\ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%weekend}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['departmentId', 'day'], 'required'],
            [['departmentId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['day'], 'string', 'max' => 10],
            [['uid'], 'unique'],
            [['departmentId'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['departmentId' => 'id']],
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
            'day' => Yii::t('app', 'Day'),
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
}
