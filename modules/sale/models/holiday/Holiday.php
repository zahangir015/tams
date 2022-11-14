<?php

namespace app\modules\sale\models\holiday;

use app\modules\account\models\Invoice;
use app\modules\sale\models\Customer;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%holiday}}".
 *
 * @property int $id
 * @property string $uid
 * @property int|null $motherId
 * @property int|null $invoiceId
 * @property int $holidayCategoryId
 * @property string $identificationNumber
 * @property int $customerId
 * @property string $customerCategory
 * @property string $type
 * @property string $issueDate
 * @property string|null $departureDate
 * @property string|null $refundRequestDate
 * @property float $quoteAmount
 * @property float $costOfSale
 * @property float $netProfit
 * @property float $receivedAmount
 * @property string|null $paymentStatus
 * @property int|null $isOnlineBooked
 * @property string $route
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Customer $customer
 * @property HolidayCategory $holidayCategory
 * @property HolidaySupplier[] $holidaySuppliers
 * @property Invoice $invoice
 */
class Holiday extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%holiday}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['holidayCategoryId', 'identificationNumber', 'customerId', 'customerCategory', 'type', 'issueDate', 'quoteAmount', 'costOfSale', 'netProfit', 'route'], 'required'],
            [['motherId', 'invoiceId', 'holidayCategoryId', 'customerId', 'isOnlineBooked', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['type', 'paymentStatus'], 'string'],
            [['issueDate', 'departureDate', 'refundRequestDate'], 'safe'],
            [['quoteAmount', 'costOfSale', 'netProfit', 'receivedAmount'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['identificationNumber'], 'string', 'max' => 32],
            [['customerCategory'], 'string', 'max' => 10],
            [['route'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['customerId'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customerId' => 'id']],
            [['holidayCategoryId'], 'exist', 'skipOnError' => true, 'targetClass' => HolidayCategory::class, 'targetAttribute' => ['holidayCategoryId' => 'id']],
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
            'motherId' => Yii::t('app', 'Mother'),
            'invoiceId' => Yii::t('app', 'Invoice'),
            'holidayCategoryId' => Yii::t('app', 'Holiday Category'),
            'identificationNumber' => Yii::t('app', 'Identification Number'),
            'customerId' => Yii::t('app', 'Customer'),
            'customerCategory' => Yii::t('app', 'Customer Category'),
            'type' => Yii::t('app', 'Type'),
            'issueDate' => Yii::t('app', 'Issue Date'),
            'departureDate' => Yii::t('app', 'Departure Date'),
            'refundRequestDate' => Yii::t('app', 'Refund Request Date'),
            'quoteAmount' => Yii::t('app', 'Quote'),
            'costOfSale' => Yii::t('app', 'Cost'),
            'netProfit' => Yii::t('app', 'Net Profit'),
            'receivedAmount' => Yii::t('app', 'Received'),
            'paymentStatus' => Yii::t('app', 'Payment Status'),
            'isOnlineBooked' => Yii::t('app', 'Is Online Booked'),
            'route' => Yii::t('app', 'Route'),
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
     * Gets query for [[HolidayCategory]].
     *
     * @return ActiveQuery
     */
    public function getHolidayCategory(): ActiveQuery
    {
        return $this->hasOne(HolidayCategory::class, ['id' => 'holidayCategoryId']);
    }

    /**
     * Gets query for [[HolidaySuppliers]].
     *
     * @return ActiveQuery
     */
    public function getHolidaySuppliers(): ActiveQuery
    {
        return $this->hasMany(HolidaySupplier::class, ['holidayId' => 'id']);
    }

    /**
     * Gets query for [[HolidayRefund]].
     *
     * @return ActiveQuery
     */
    public function getHolidayRefund(): ActiveQuery
    {
        return $this->hasOne(HolidayRefund::class, ['holidayId' => 'id']);
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
