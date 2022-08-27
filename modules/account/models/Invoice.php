<?php

namespace app\modules\account\models;

use Yii;

/**
 * This is the model class for table "{{%invoice}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $customerId
 * @property string $invoiceNumber
 * @property string $date
 * @property string|null $expectedPaymentDate
 * @property float|null $paidAmount
 * @property float|null $dueAmount
 * @property float|null $discountedAmount
 * @property float|null $refundAdjustmentAmount
 * @property string|null $remarks
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Customer $customer
 * @property Ticket[] $tickets
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%invoice}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'customerId', 'invoiceNumber', 'date', 'createdBy', 'createdAt'], 'required'],
            [['customerId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['date', 'expectedPaymentDate'], 'safe'],
            [['paidAmount', 'dueAmount', 'discountedAmount', 'refundAdjustmentAmount'], 'number'],
            [['remarks'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['invoiceNumber'], 'string', 'max' => 64],
            [['uid'], 'unique'],
            [['invoiceNumber'], 'unique'],
            [['customerId'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customerId' => 'id']],
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
            'customerId' => Yii::t('app', 'Customer ID'),
            'invoiceNumber' => Yii::t('app', 'Invoice Number'),
            'date' => Yii::t('app', 'Date'),
            'expectedPaymentDate' => Yii::t('app', 'Expected Payment Date'),
            'paidAmount' => Yii::t('app', 'Paid Amount'),
            'dueAmount' => Yii::t('app', 'Due Amount'),
            'discountedAmount' => Yii::t('app', 'Discounted Amount'),
            'refundAdjustmentAmount' => Yii::t('app', 'Refund Adjustment Amount'),
            'remarks' => Yii::t('app', 'Remarks'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customerId']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::className(), ['invoiceId' => 'id']);
    }
}
