<?php

namespace app\modules\account\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%journal}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $journalNumber
 * @property string $postedDate
 * @property float|null $debit
 * @property float|null $credit
 * @property float|null $outOfBalance
 * @property int $status
 * @property int $createdAt
 * @property int|null $updatedAt
 * @property int $createdBy
 * @property int|null $updatedBy
 *
 * @property JournalEntry[] $journalEntries
 */
class Journal extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%journal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'journalNumber', 'postedDate'], 'required'],
            [['postedDate'], 'safe'],
            [['debit', 'credit', 'outOfBalance'], 'number'],
            [['status', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['journalNumber'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['journalNumber'], 'unique'],
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
            'journalNumber' => Yii::t('app', 'Journal Number'),
            'postedDate' => Yii::t('app', 'Posted Date'),
            'debit' => Yii::t('app', 'Debit'),
            'credit' => Yii::t('app', 'Credit'),
            'outOfBalance' => Yii::t('app', 'Out Of Balance'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedBy' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[JournalEntries]].
     *
     * @return ActiveQuery
     */
    public function getJournalEntries(): ActiveQuery
    {
        return $this->hasMany(JournalEntry::class, ['journalId' => 'id']);
    }
}
