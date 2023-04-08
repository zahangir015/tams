<?php

namespace app\modules\sale\models\visa;

use app\modules\account\models\Invoice;
use app\modules\sale\models\Customer;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%visa}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property int|null $motherId
 * @property int|null $invoiceId
 * @property string $identificationNumber
 * @property int $customerId
 * @property string $customerCategory
 * @property string $type
 * @property string $issueDate
 * @property string|null $refundRequestDate
 * @property int $totalQuantity
 * @property int $processStatus
 * @property float $quoteAmount
 * @property float $costOfSale
 * @property float $netProfit
 * @property float $receivedAmount
 * @property string|null $paymentStatus
 * @property int|null $isOnlineBooked
 * @property string|null $reference
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Customer $customer
 * @property Invoice $invoice
 * @property VisaSupplier[] $visaSuppliers
 */
class Visa extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%visa}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['identificationNumber', 'customerId', 'customerCategory', 'issueDate', 'totalQuantity', 'quoteAmount', 'costOfSale', 'netProfit'], 'required'],
            [['motherId', 'invoiceId', 'customerId', 'totalQuantity', 'processStatus', 'isOnlineBooked', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['type', 'paymentStatus'], 'string'],
            [['issueDate', 'refundRequestDate'], 'safe'],
            [['quoteAmount', 'costOfSale', 'netProfit', 'receivedAmount'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['identificationNumber'], 'string', 'max' => 32],
            [['customerCategory'], 'string', 'max' => 10],
            [['reference'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['identificationNumber'], 'unique'],
            [['customerId'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customerId' => 'id']],
            [['invoiceId'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::class, 'targetAttribute' => ['invoiceId' => 'id']],
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
            'motherId' => Yii::t('app', 'Mother Visa'),
            'invoiceId' => Yii::t('app', 'Invoice'),
            'identificationNumber' => Yii::t('app', 'Identification Number'),
            'customerId' => Yii::t('app', 'Customer'),
            'customerCategory' => Yii::t('app', 'Customer Category'),
            'type' => Yii::t('app', 'Type'),
            'issueDate' => Yii::t('app', 'Passport/Document received date'),
            'refundRequestDate' => Yii::t('app', 'Refund Request Date'),
            'totalQuantity' => Yii::t('app', 'Total Quantity'),
            'processStatus' => Yii::t('app', 'Process Status'),
            'quoteAmount' => Yii::t('app', 'Quote Amount'),
            'costOfSale' => Yii::t('app', 'Cost Of Sale'),
            'netProfit' => Yii::t('app', 'Net Profit'),
            'receivedAmount' => Yii::t('app', 'Received Amount'),
            'paymentStatus' => Yii::t('app', 'Payment Status'),
            'isOnlineBooked' => Yii::t('app', 'Is Online Booked'),
            'reference' => Yii::t('app', 'Reference'),
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
     * Gets query for [[Invoice]].
     *
     * @return ActiveQuery
     */
    public function getInvoice(): ActiveQuery
    {
        return $this->hasOne(Invoice::class, ['id' => 'invoiceId']);
    }

    /**
     * Gets query for [[VisaSuppliers]].
     *
     * @return ActiveQuery
     */
    public function getVisaSuppliers(): ActiveQuery
    {
        return $this->hasMany(VisaSupplier::class, ['visaId' => 'id']);
    }

    /**
     * Gets query for [[VisaRefund]].
     *
     * @return ActiveQuery
     */
    public function getVisaRefund(): ActiveQuery
    {
        return $this->hasOne(VisaRefund::class, ['visaId' => 'id']);
    }
}
