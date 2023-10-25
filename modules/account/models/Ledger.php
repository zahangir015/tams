<?php

namespace app\modules\account\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%ledger}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
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
class Ledger extends ActiveRecord
{
    use BehaviorTrait;

    public $customer;
    public $supplier;
    public $bank;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%ledger}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'date', 'refId', 'refModel'], 'required'],
            [['date'], 'safe'],
            [['refId', 'subRefId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
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
    public function attributeLabels(): array
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

    public static function getReferenceName($refId, $refModel)
    {
        $model = $refModel::findOne(['id' => $refId]);
        return $model ? $model->name : null;
    }

    public static function getSubReferenceName($subRefId, $subRefModel)
    {
        $model = $subRefModel::findOne(['id' => $subRefId]);

        if (str_contains($subRefModel, 'Invoice')) {
            return $model->invoiceNumber;
        } elseif (str_contains($subRefModel, 'Bill')) {
            return $model->billNumber;
        } elseif (str_contains($subRefModel, 'Expense')) {
            return $model->identificationNumber;
        }elseif (str_contains($subRefModel, 'Journal')) {
            return $model->journalNumber;
        } else {
            return $model->identificationNumber;
        }

    }
}
