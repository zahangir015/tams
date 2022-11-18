<?php

namespace app\modules\sale\models\visa;

use app\modules\account\models\RefundTransaction;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%visa_refund}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $visaId
 * @property int|null $refundTransactionId
 * @property int $refId
 * @property string $refModel
 * @property string $refundRequestDate
 * @property string|null $refundStatus
 * @property string|null $refundMedium
 * @property string|null $refundMethod
 * @property float|null $supplierRefundCharge
 * @property float|null $serviceCharge
 * @property int|null $isRefunded
 * @property string|null $refundDate
 * @property float|null $refundedAmount
 * @property string|null $remarks
 * @property int|null $status
 *
 * @property RefundTransaction $refundTransaction
 */
class VisaRefund extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%visa_refund}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['visaId', 'refId', 'refModel', 'refundRequestDate'], 'required'],
            [['visaId', 'refundTransactionId', 'refId', 'isRefunded', 'status'], 'integer'],
            [['refundRequestDate', 'refundDate'], 'safe'],
            [['refundStatus', 'refundMedium', 'refundMethod', 'remarks'], 'string'],
            [['supplierRefundCharge', 'serviceCharge', 'refundedAmount'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['refModel'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['refundTransactionId'], 'exist', 'skipOnError' => true, 'targetClass' => RefundTransaction::class, 'targetAttribute' => ['refundTransactionId' => 'id']],
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
            'visaId' => Yii::t('app', 'Visa ID'),
            'refundTransactionId' => Yii::t('app', 'Refund Transaction ID'),
            'refId' => Yii::t('app', 'Ref ID'),
            'refModel' => Yii::t('app', 'Ref Model'),
            'refundRequestDate' => Yii::t('app', 'Refund Request Date'),
            'refundStatus' => Yii::t('app', 'Refund Status'),
            'refundMedium' => Yii::t('app', 'Refund Medium'),
            'refundMethod' => Yii::t('app', 'Refund Method'),
            'supplierRefundCharge' => Yii::t('app', 'Supplier Refund Charge'),
            'serviceCharge' => Yii::t('app', 'Service Charge'),
            'isRefunded' => Yii::t('app', 'Is Refunded'),
            'refundDate' => Yii::t('app', 'Refund Date'),
            'refundedAmount' => Yii::t('app', 'Refunded Amount'),
            'remarks' => Yii::t('app', 'Remarks'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[RefundTransaction]].
     *
     * @return ActiveQuery
     */
    public function getRefundTransaction(): ActiveQuery
    {
        return $this->hasOne(RefundTransaction::class, ['id' => 'refundTransactionId']);
    }
}
