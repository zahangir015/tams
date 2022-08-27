<?php

namespace app\modules\account\models;

use Yii;

/**
 * This is the model class for table "{{%ledger}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $title
 * @property string $date
 * @property string|null $reference
 * @property int $refId
 * @property string $refModel
 * @property int|null $subRefId
 * @property string|null $subRefModel
 * @property float|null $debit
 * @property float|null $credit
 * @property float|null $balance
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 */
class Ledger extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ledger}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'title', 'date', 'refId', 'refModel', 'createdBy', 'createdAt'], 'required'],
            [['date'], 'safe'],
            [['refId', 'subRefId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['debit', 'credit', 'balance'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['title', 'refModel', 'subRefModel'], 'string', 'max' => 150],
            [['reference'], 'string', 'max' => 255],
            [['uid'], 'unique'],
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
            'title' => Yii::t('app', 'Title'),
            'date' => Yii::t('app', 'Date'),
            'reference' => Yii::t('app', 'Reference'),
            'refId' => Yii::t('app', 'Ref ID'),
            'refModel' => Yii::t('app', 'Ref Model'),
            'subRefId' => Yii::t('app', 'Sub Ref ID'),
            'subRefModel' => Yii::t('app', 'Sub Ref Model'),
            'debit' => Yii::t('app', 'Debit'),
            'credit' => Yii::t('app', 'Credit'),
            'balance' => Yii::t('app', 'Balance'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }
}
