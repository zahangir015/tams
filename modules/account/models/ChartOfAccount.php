<?php

namespace app\modules\account\models;

use app\components\GlobalConstant;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%chart_of_account}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
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
class ChartOfAccount extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%chart_of_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['accountTypeId', 'accountGroupId', 'code', 'name'], 'required'],
            [['accountTypeId', 'accountGroupId', 'status', 'createdAt', 'createdBy', 'updatedAt', 'updatedBy', 'agencyId'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['code'], 'string', 'max' => 10],
            [['name', 'description'], 'string', 'max' => 150],
            [['reportType'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['code', 'agencyId'], 'unique', 'targetAttribute' => ['code', 'agencyId']],
            [['accountGroupId'], 'exist', 'skipOnError' => true, 'targetClass' => AccountGroup::class, 'targetAttribute' => ['accountGroupId' => 'id']],
            [['accountTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => AccountType::class, 'targetAttribute' => ['accountTypeId' => 'id']],
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
            'accountTypeId' => Yii::t('app', 'Account Type'),
            'accountGroupId' => Yii::t('app', 'Account Group'),
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
     * @return ActiveQuery
     */
    public function getAccountGroup(): ActiveQuery
    {
        return $this->hasOne(AccountGroup::class, ['id' => 'accountGroupId']);
    }

    /**
     * Gets query for [[AccountType]].
     *
     * @return ActiveQuery
     */
    public function getAccountType(): ActiveQuery
    {
        return $this->hasOne(AccountType::class, ['id' => 'accountTypeId']);
    }

    /**
     * Gets query for [[JournalEntries]].
     *
     * @return ActiveQuery
     */
    public function getJournalEntries(): ActiveQuery
    {
        return $this->hasMany(JournalEntry::class, ['accountId' => 'id']);
    }

    /**
     * Gets query for [[ChartOfAccounts]].
     *
     * @return array
     */
    public static function query($query): array
    {
        return self::find()
            ->select(['id', 'name', 'code'])
            ->where(['like', 'name', $query])
            ->orWhere(['like', 'code', $query])
            ->andWhere([self::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([self::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId])
            ->all();
    }
}
