<?php

namespace app\modules\account\models;

use Yii;

/**
 * This is the model class for table "{{%refund_transaction_detail}}".
 *
 * @property int $id
 * @property int $refundTransactionID
 * @property int $refId
 * @property string $refModel
 * @property float|null $payableAmount
 * @property float|null $receivableAmount
 * @property float|null $totalAmount
 * @property int $status
 */
class RefundTransactionDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%refund_transaction_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['refundTransactionID', 'refId', 'refModel'], 'required'],
            [['refundTransactionID', 'refId', 'status'], 'integer'],
            [['payableAmount', 'receivableAmount', 'totalAmount'], 'number'],
            [['refModel'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'refundTransactionID' => Yii::t('app', 'Refund Transaction ID'),
            'refId' => Yii::t('app', 'Ref ID'),
            'refModel' => Yii::t('app', 'Ref Model'),
            'payableAmount' => Yii::t('app', 'Payable Amount'),
            'receivableAmount' => Yii::t('app', 'Receivable Amount'),
            'totalAmount' => Yii::t('app', 'Total Amount'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
