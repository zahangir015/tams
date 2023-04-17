<?php

namespace app\modules\hrm\models;

use app\components\GlobalConstant;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "department".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property int|null $parentId
 * @property string $name
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 */
class Department extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%department}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['parentId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 120],
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
            'parentId' => Yii::t('app', 'Parent ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getParent(): ActiveQuery
    {
        return $this->hasOne(self::class, ['parentId' => 'id']);
    }

    public static function query(mixed $query)
    {
        return self::find()
            ->select(['id', 'name', 'status'])
            ->where(['like', 'name', $query])
            ->andWhere([self::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([self::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId])
            ->all();
    }

}
