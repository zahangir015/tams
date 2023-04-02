<?php

namespace app\modules\agent\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%plan}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $userLimit
 * @property float $monthlySubscriptionFee
 * @property float $yearlySubscriptionFee
 * @property string|null $modules
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Agency[] $agencies
 */
class Plan extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%plan}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'userLimit', 'monthlySubscriptionFee', 'yearlySubscriptionFee'], 'required'],
            [['monthlySubscriptionFee', 'yearlySubscriptionFee'], 'number'],
            [['modules'], 'string'],
            [['status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name', 'userLimit'], 'string', 'max' => 255],
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
            'name' => Yii::t('app', 'Name'),
            'userLimit' => Yii::t('app', 'User Limit'),
            'monthlySubscriptionFee' => Yii::t('app', 'Monthly Subscription Fee'),
            'yearlySubscriptionFee' => Yii::t('app', 'Yearly Subscription Fee'),
            'modules' => Yii::t('app', 'Modules'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Agencies]].
     *
     * @return ActiveQuery
     */
    public function getAgencies(): ActiveQuery
    {
        return $this->hasMany(Agency::class, ['planId' => 'id']);
    }
}
