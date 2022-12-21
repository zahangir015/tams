<?php

namespace app\modules\account\models;

use app\modules\sale\models\ticket\TicketRefund;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%refund_transaction}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $refId
 * @property string $refModel
 * @property float|null $payableAmount
 * @property float|null $receivableAmount
 * @property float|null $totalAmount
 * @property string|null $paymentStatus
 * @property float|null $adjustedAmount
 * @property int|null $isAdjusted
 * @property string|null $remarks
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property TicketRefund[] $ticketRefunds
 */
class RefundTransaction extends ActiveRecord
{
    use BehaviorTrait;

    public $dateRange;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%refund_transaction}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['refId', 'refModel'], 'required'],
            [['refId', 'isAdjusted', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['payableAmount', 'receivableAmount', 'totalAmount', 'adjustedAmount'], 'number'],
            [['paymentStatus', 'remarks'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['refModel'], 'string', 'max' => 150],
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
            'refId' => Yii::t('app', 'Ref ID'),
            'refModel' => Yii::t('app', 'Ref Model'),
            'payableAmount' => Yii::t('app', 'Payable Amount'),
            'receivableAmount' => Yii::t('app', 'Receivable Amount'),
            'totalAmount' => Yii::t('app', 'Total Amount'),
            'paymentStatus' => Yii::t('app', 'Payment Status'),
            'adjustedAmount' => Yii::t('app', 'Adjusted Amount'),
            'isAdjusted' => Yii::t('app', 'Is Adjusted'),
            'remarks' => Yii::t('app', 'Remarks'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[TicketRefunds]].
     *
     * @return ActiveQuery
     */
    public function getTicketRefunds(): ActiveQuery
    {
        return $this->hasMany(TicketRefund::className(), ['refundTransactionId' => 'id']);
    }
}
