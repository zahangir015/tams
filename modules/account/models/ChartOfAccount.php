<?php

namespace app\modules\account\models;

use Yii;

/**
 * This is the model class for table "{{%chart_of_account}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $accountTypeId
 * @property int $accountGroupId
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string|null $reportType
 * @property int $status
 * @property int $createdAt
 * @property int $createdBy
 * @property int|null $updatedAt
 * @property int|null $updatedBy
 *
 * @property AccountGroup $accountGroup
 * @property AccountType $accountType
 * @property JournalEntry[] $journalEntries
 */
class ChartOfAccount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%chart_of_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'accountTypeId', 'accountGroupId', 'code', 'name', 'status', 'createdAt', 'createdBy'], 'required'],
            [['accountTypeId', 'accountGroupId', 'status', 'createdAt', 'createdBy', 'updatedAt', 'updatedBy'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['code'], 'string', 'max' => 10],
            [['name', 'description'], 'string', 'max' => 150],
            [['reportType'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['code'], 'unique'],
            [['accountGroupId'], 'exist', 'skipOnError' => true, 'targetClass' => AccountGroup::class, 'targetAttribute' => ['accountGroupId' => 'id']],
            [['accountTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => AccountType::class, 'targetAttribute' => ['accountTypeId' => 'id']],
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
            'accountTypeId' => Yii::t('app', 'Account Type ID'),
            'accountGroupId' => Yii::t('app', 'Account Group ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'reportType' => Yii::t('app', 'Report Type'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[AccountGroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccountGroup()
    {
        return $this->hasOne(AccountGroup::class, ['id' => 'accountGroupId']);
    }

    /**
     * Gets query for [[AccountType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccountType()
    {
        return $this->hasOne(AccountType::class, ['id' => 'accountTypeId']);
    }

    /**
     * Gets query for [[JournalEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJournalEntries()
    {
        return $this->hasMany(JournalEntry::class, ['accountId' => 'id']);
    }
}
