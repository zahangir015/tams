<?php

namespace app\modules\sale\models\hotel;

use app\modules\account\models\Invoice;
use app\modules\sale\models\Customer;
use app\traits\BehaviorTrait;
use app\traits\UniversalColumnTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%hotel}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property int|null $motherId
 * @property int|null $invoiceId
 * @property string $identificationNumber
 * @property int $customerId
 * @property string $customerCategory
 * @property string|null $voucherNumber
 * @property string|null $reservationCode
 * @property string $type
 * @property string $issueDate
 * @property string|null $refundRequestDate
 * @property string $checkInDate
 * @property string $checkOutDate
 * @property string|null $freeCancellationDate
 * @property int $totalNights
 * @property string $route
 * @property int|null $isRefundable
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
 * @property HotelSupplier[] $hotelSuppliers
 * @property Invoice $invoice
 */
class Hotel extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%hotel}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['identificationNumber', 'customerId', 'customerCategory', 'issueDate', 'checkInDate', 'checkOutDate', 'totalNights', 'route', 'quoteAmount', 'costOfSale', 'netProfit'], 'required'],
            [['motherId', 'invoiceId', 'customerId', 'totalNights', 'isRefundable', 'isOnlineBooked', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['type', 'paymentStatus'], 'string'],
            [['issueDate', 'refundRequestDate', 'checkInDate', 'checkOutDate', 'freeCancellationDate'], 'safe'],
            [['quoteAmount', 'costOfSale', 'netProfit', 'receivedAmount'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['identificationNumber'], 'string', 'max' => 32],
            [['customerCategory'], 'string', 'max' => 10],
            [['voucherNumber', 'reservationCode', 'route', 'reference'], 'string', 'max' => 255],
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
            'motherId' => Yii::t('app', 'Mother ID'),
            'invoiceId' => Yii::t('app', 'Invoice ID'),
            'identificationNumber' => Yii::t('app', 'Identification Number'),
            'customerId' => Yii::t('app', 'Customer ID'),
            'customerCategory' => Yii::t('app', 'Customer Category'),
            'voucherNumber' => Yii::t('app', 'Voucher Number'),
            'reservationCode' => Yii::t('app', 'Reservation Code'),
            'type' => Yii::t('app', 'Type'),
            'issueDate' => Yii::t('app', 'Issue Date'),
            'refundRequestDate' => Yii::t('app', 'Refund Request Date'),
            'checkInDate' => Yii::t('app', 'Check In Date'),
            'checkOutDate' => Yii::t('app', 'Check Out Date'),
            'freeCancellationDate' => Yii::t('app', 'Free Cancellation Date'),
            'totalNights' => Yii::t('app', 'Total Nights'),
            'route' => Yii::t('app', 'Destination'),
            'isRefundable' => Yii::t('app', 'Is Refundable'),
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
     * Gets query for [[HotelSuppliers]].
     *
     * @return ActiveQuery
     */
    public function getHotelSuppliers(): ActiveQuery
    {
        return $this->hasMany(HotelSupplier::class, ['hotelId' => 'id']);
    }

    /**
     * Gets query for [[HotelRefund]].
     *
     * @return ActiveQuery
     */
    public function getHotelRefund(): ActiveQuery
    {
        return $this->hasOne(HotelRefund::class, ['hotelId' => 'id']);
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
}
