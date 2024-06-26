<?php

namespace app\modules\account\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%transaction}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property string $transactionNumber
 * @property int $refId
 * @property string $refModel
 * @property int $subRefId
 * @property string $subRefModel
 * @property int $bankId
 * @property string $reference Cheque number/ Payment reference number
 * @property float $paidAmount
 * @property float $paymentCharge
 * @property string $paymentDate
 * @property string|null $paymentMode
 * @property string|null $remarks
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property BankAccount $bank
 */
class Transaction extends ActiveRecord
{
    use BehaviorTrait;

    public $refundIds;
    public $advancePayments;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%transaction}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['transactionNumber', 'refId', 'refModel', 'subRefId', 'subRefModel', 'bankId', 'reference', 'paidAmount', 'paymentCharge', 'paymentDate', 'paymentMode'], 'required'],
            [['refId', 'subRefId', 'bankId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['paidAmount', 'paymentCharge'], 'number'],
            [['paymentDate'], 'safe'],
            [['paymentMode'], 'string'],
            ['transactionNumber', 'unique'],
            [['uid'], 'string', 'max' => 36],
            [['transactionNumber'], 'string', 'max' => 64],
            [['refModel', 'subRefModel'], 'string', 'max' => 150],
            [['reference', 'remarks'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['bankId'], 'exist', 'skipOnError' => true, 'targetClass' => BankAccount::className(), 'targetAttribute' => ['bankId' => 'id']],
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
            'transactionNumber' => Yii::t('app', 'Transaction Number'),
            'refId' => Yii::t('app', 'Ref ID'),
            'refModel' => Yii::t('app', 'Ref Model'),
            'subRefId' => Yii::t('app', 'Sub Ref ID'),
            'subRefModel' => Yii::t('app', 'Sub Ref Model'),
            'bankId' => Yii::t('app', 'Bank ID'),
            'reference' => Yii::t('app', 'Cheque/Payment reference'),
            'paidAmount' => Yii::t('app', 'Paid Amount'),
            'paymentCharge' => Yii::t('app', 'Payment Charge'),
            'paymentDate' => Yii::t('app', 'Payment Date'),
            'paymentMode' => Yii::t('app', 'Payment Mode'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Bank]].
     *
     * @return ActiveQuery
     */
    public function getBank(): ActiveQuery
    {
        return $this->hasOne(BankAccount::class, ['id' => 'bankId']);
    }
}
