<?php

namespace app\modules\account\models;

use app\components\GlobalConstant;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%account_type}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property int $status
 * @property int $createdAt
 * @property int|null $updatedAt
 * @property int $createdBy
 * @property int|null $updatedBy
 *
 * @property ChartOfAccount[] $chartOfAccounts
 */
class AccountType extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%account_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'status'], 'required'],
            [['status', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 150],
            [['uid'], 'unique'],
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
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedBy' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[ChartOfAccounts]].
     *
     * @return ActiveQuery
     */
    public function getChartOfAccounts(): ActiveQuery
    {
        return $this->hasMany(ChartOfAccount::class, ['accountTypeId' => 'id']);
    }

    public static function query(mixed $query)
    {
        return self::find()
            ->select(['id', 'name', 'status'])
            ->where(['like', 'name', $query])
            ->andWhere([self::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}
