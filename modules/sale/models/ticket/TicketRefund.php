<?php

namespace app\modules\sale\models\ticket;

use app\modules\account\models\RefundTransaction;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%ticket_refund}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $ticketId
 * @property int $refId
 * @property int|null $refundTransactionId
 * @property string $refModel
 * @property string $refundRequestDate
 * @property string|null $refundStatus
 * @property string|null $refundMedium
 * @property string|null $refundMethod
 * @property float|null $supplierRefundCharge
 * @property float|null $airlineRefundCharge
 * @property float|null $serviceCharge
 * @property int|null $isRefunded
 * @property string|null $refundDate
 * @property float|null $refundedAmount
 * @property string|null $remarks
 * @property int|null $status
 *
 * @property RefundTransaction $refundTransaction
 * @property Ticket $ticket
 */
class TicketRefund extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%ticket_refund}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'ticketId', 'refId', 'refModel', 'refundRequestDate'], 'required'],
            [['ticketId', 'refId', 'refundTransactionId', 'isRefunded', 'status'], 'integer'],
            [['refundRequestDate', 'refundDate'], 'safe'],
            [['refundStatus', 'refundMedium', 'refundMethod', 'remarks'], 'string'],
            [['supplierRefundCharge', 'airlineRefundCharge', 'serviceCharge', 'refundedAmount'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['refModel'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['refundTransactionId'], 'exist', 'skipOnError' => true, 'targetClass' => RefundTransaction::className(), 'targetAttribute' => ['refundTransactionId' => 'id']],
            [['ticketId'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::className(), 'targetAttribute' => ['ticketId' => 'id']],
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
            'ticketId' => Yii::t('app', 'Ticket ID'),
            'refId' => Yii::t('app', 'Ref ID'),
            'refundTransactionId' => Yii::t('app', 'Refund Transaction ID'),
            'refModel' => Yii::t('app', 'Ref Model'),
            'refundRequestDate' => Yii::t('app', 'Refund Request Date'),
            'refundStatus' => Yii::t('app', 'Refund Status'),
            'refundMedium' => Yii::t('app', 'Refund Medium'),
            'refundMethod' => Yii::t('app', 'Refund Method'),
            'supplierRefundCharge' => Yii::t('app', 'Supplier Refund Charge'),
            'airlineRefundCharge' => Yii::t('app', 'Airline Refund Charge'),
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
        return $this->hasOne(RefundTransaction::className(), ['id' => 'refundTransactionId']);
    }

    /**
     * Gets query for [[Ticket]].
     *
     * @return ActiveQuery
     */
    public function getTicket(): ActiveQuery
    {
        return $this->hasOne(Ticket::className(), ['id' => 'ticketId']);
    }
}
