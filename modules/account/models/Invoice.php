<?php

namespace app\modules\account\models;

use app\modules\sale\models\Customer;
use app\modules\sale\models\ticket\Ticket;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
class Invoice extends ActiveRecord
{
    use BehaviorTrait;
    public $dateRange;
    public $invoiceFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%invoice}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['customerId'], 'required'],
            [['customerId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['date', 'expectedPaymentDate'], 'safe'],
            [['paidAmount', 'dueAmount', 'discountedAmount', 'refundAdjustmentAmount'], 'number'],
            [['remarks'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['invoiceNumber'], 'string', 'max' => 64],
            [['uid'], 'unique'],
            [['invoiceNumber'], 'unique'],
            [['customerId'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customerId' => 'id']],
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
     * @return ActiveQuery
     */
    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(Customer::class, ['id' => 'customerId']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return ActiveQuery
     */
    public function getTickets(): ActiveQuery
    {
        return $this->hasMany(Ticket::class, ['invoiceId' => 'id']);
    }

    /**
     * Gets query for [[InvoiceDetail]].
     *
     * @return ActiveQuery
     */
    public function getDetails(): ActiveQuery
    {
        return $this->hasMany(InvoiceDetail::class, ['invoiceId' => 'id']);
    }

    /**
     * Gets query for [[InvoiceDetail]].
     *
     * @return ActiveQuery
     */
    public function getTransactions(): ActiveQuery
    {
        return $this->hasMany(Transaction::class, ['refId' => 'id'])->onCondition(['refModel' => Invoice::class]);
    }
}
