<?php

namespace app\modules\account\models;

use Yii;

/**
 * This is the model class for table "{{%transaction}}".
 *
 * @property int $id
 * @property string $uid
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
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property BankAccount $bank
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%transaction}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'transactionNumber', 'refId', 'refModel', 'subRefId', 'subRefModel', 'bankId', 'reference', 'paidAmount', 'paymentCharge', 'paymentDate', 'createdBy', 'createdAt'], 'required'],
            [['refId', 'subRefId', 'bankId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['paidAmount', 'paymentCharge'], 'number'],
            [['paymentDate'], 'safe'],
            [['paymentMode'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['transactionNumber'], 'string', 'max' => 64],
            [['refModel', 'subRefModel'], 'string', 'max' => 150],
            [['reference'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['bankId'], 'exist', 'skipOnError' => true, 'targetClass' => BankAccount::className(), 'targetAttribute' => ['bankId' => 'id']],
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
            'transactionNumber' => Yii::t('app', 'Transaction Number'),
            'refId' => Yii::t('app', 'Ref ID'),
            'refModel' => Yii::t('app', 'Ref Model'),
            'subRefId' => Yii::t('app', 'Sub Ref ID'),
            'subRefModel' => Yii::t('app', 'Sub Ref Model'),
            'bankId' => Yii::t('app', 'Bank ID'),
            'reference' => Yii::t('app', 'Cheque number/ Payment reference number'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(BankAccount::className(), ['id' => 'bankId']);
    }
}
