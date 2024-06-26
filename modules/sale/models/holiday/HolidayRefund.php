<?php

namespace app\modules\sale\models\holiday;

use app\modules\account\models\RefundTransaction;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%holiday_refund}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $holidayId
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
class HolidayRefund extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%holiday_refund}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'holidayId', 'refId', 'refModel', 'refundRequestDate'], 'required'],
            [['holidayId', 'refundTransactionId', 'refId', 'isRefunded', 'status'], 'integer'],
            [['refundRequestDate', 'refundDate'], 'safe'],
            [['refundStatus', 'refundMedium', 'refundMethod', 'remarks'], 'string'],
            [['supplierRefundCharge', 'serviceCharge', 'refundedAmount'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['refModel'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['refundTransactionId'], 'exist', 'skipOnError' => true, 'targetClass' => RefundTransaction::className(), 'targetAttribute' => ['refundTransactionId' => 'id']],
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
            'holidayId' => Yii::t('app', 'Holiday ID'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getRefundTransaction()
    {
        return $this->hasOne(RefundTransaction::className(), ['id' => 'refundTransactionId']);
    }
}
