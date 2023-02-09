<?php

namespace app\modules\account\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%account_group}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $accountTypeId
 * @property string $name
 * @property string $code
 * @property int $status
 * @property int $createdAt
 * @property int $createdBy
 * @property int|null $updatedAt
 * @property int|null $updatedBy
 *
 * @property ChartOfAccount[] $chartOfAccounts
 */
class AccountGroup extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%account_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['accountTypeId', 'name', 'code', 'status'], 'required'],
            [['accountTypeId', 'status', 'createdAt', 'createdBy', 'updatedAt', 'updatedBy'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 175],
            [['code'], 'string', 'max' => 10],
            [['uid'], 'unique'],
            [['code'], 'unique'],
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
            'accountTypeId' => Yii::t('app', 'Account Type ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
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
        return $this->hasMany(ChartOfAccount::class, ['accountGroupId' => 'id']);
    }
}
