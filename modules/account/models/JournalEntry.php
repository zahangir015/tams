<?php

namespace app\modules\account\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%journal_entry}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $journalId
 * @property int $accountId
 * @property string|null $accountName
 * @property int $refId
 * @property string $refModel
 * @property float|null $debit
 * @property float|null $credit
 * @property string|null $details
 * @property int $status
 *
 * @property ChartOfAccount $account
 * @property Journal $journal
 */
class JournalEntry extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%journal_entry}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['journalId'], 'required'],
            [['journalId', 'accountId', 'refId', 'status'], 'integer'],
            [['debit', 'credit'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['accountName', 'details'], 'string', 'max' => 255],
            [['refModel'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['accountId'], 'exist', 'skipOnError' => true, 'targetClass' => ChartOfAccount::class, 'targetAttribute' => ['accountId' => 'id']],
            [['journalId'], 'exist', 'skipOnError' => true, 'targetClass' => Journal::class, 'targetAttribute' => ['journalId' => 'id']],
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
            'journalId' => Yii::t('app', 'Journal ID'),
            'accountId' => Yii::t('app', 'Account ID'),
            'accountName' => Yii::t('app', 'Account Name'),
            'refId' => Yii::t('app', 'Ref ID'),
            'refModel' => Yii::t('app', 'Ref Model'),
            'debit' => Yii::t('app', 'Debit'),
            'credit' => Yii::t('app', 'Credit'),
            'details' => Yii::t('app', 'Details'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[Account]].
     *
     * @return ActiveQuery
     */
    public function getAccount(): ActiveQuery
    {
        return $this->hasOne(ChartOfAccount::class, ['id' => 'accountId']);
    }

    /**
     * Gets query for [[Journal]].
     *
     * @return ActiveQuery
     */
    public function getJournal(): ActiveQuery
    {
        return $this->hasOne(Journal::class, ['id' => 'journalId']);
    }
}
