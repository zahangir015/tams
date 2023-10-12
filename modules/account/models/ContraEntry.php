<?php

namespace app\modules\account\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%contra_entry}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property string $identificationNumber
 * @property int $bankFrom
 * @property int $bankTo
 * @property float|null $amount
 * @property string $paymentDate
 * @property string|null $remarks
 * @property int $status
 * @property int $createdAt
 * @property int|null $updatedAt
 * @property int $createdBy
 * @property int|null $updatedBy
 */
class ContraEntry extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%contra_entry}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['identificationNumber', 'bankFrom', 'bankTo', 'paymentDate'], 'required'],
            [['bankFrom', 'bankTo', 'status', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy', 'agencyId'], 'integer'],
            [['amount'], 'number'],
            [['paymentDate'], 'safe'],
            [['uid'], 'string', 'max' => 36],
            [['identificationNumber', 'remarks'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['bankFrom', 'bankTo'], 'unique', 'attributes' => ['bankFrom', 'bankTo']],
            [['identificationNumber'], 'unique'],
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
            'identificationNumber' => Yii::t('app', 'Identification Number'),
            'bankFrom' => Yii::t('app', 'Bank From'),
            'bankTo' => Yii::t('app', 'Bank To'),
            'amount' => Yii::t('app', 'Amount'),
            'paymentDate' => Yii::t('app', 'Payment Date'),
            'remarks' => Yii::t('app', 'Remarks'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedBy' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[BankAccount]].
     *
     * @return ActiveQuery
     */
    public function getTransferredFrom(): ActiveQuery
    {
        return $this->hasOne(BankAccount::class, ['id' => 'bankFrom']);
    }

    /**
     * Gets query for [[BankAccount]].
     *
     * @return ActiveQuery
     */
    public function getTransferredTo(): ActiveQuery
    {
        return $this->hasOne(BankAccount::class, ['id' => 'bankTo']);
    }
}
