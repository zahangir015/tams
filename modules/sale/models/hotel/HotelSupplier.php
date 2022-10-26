<?php

namespace app\modules\sale\models\hotel;

use app\modules\account\models\Bill;
use app\modules\sale\models\Supplier;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%hotel_supplier}}".
 *
 * @property int $id
 * @property string $uid
 * @property int|null $motherHotelSupplierId
 * @property int $hotelId
 * @property int|null $billId
 * @property int $supplierId
 * @property string $supplierRef
 * @property string $issueDate
 * @property string|null $refundRequestDate
 * @property string $type
 * @property string|null $serviceDetails
 * @property int $numberOfNights
 * @property int $quantity
 * @property float $unitPrice
 * @property float $costOfSale
 * @property float|null $paidAmount
 * @property string|null $paymentStatus
 * @property int|null $status
 *
 * @property Bill $bill
 * @property Hotel $hotel
 * @property Supplier $supplier
 */
class HotelSupplier extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%hotel_supplier}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['hotelId', 'supplierId', 'supplierRef', 'issueDate', 'type', 'numberOfNights', 'quantity', 'unitPrice', 'costOfSale'], 'required'],
            [['motherHotelSupplierId', 'hotelId', 'billId', 'supplierId', 'numberOfNights', 'quantity', 'status'], 'integer'],
            [['issueDate', 'refundRequestDate'], 'safe'],
            [['type', 'paymentStatus'], 'string'],
            [['unitPrice', 'costOfSale', 'paidAmount'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['supplierRef', 'serviceDetails'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['billId'], 'exist', 'skipOnError' => true, 'targetClass' => Bill::className(), 'targetAttribute' => ['billId' => 'id']],
            [['hotelId'], 'exist', 'skipOnError' => true, 'targetClass' => Hotel::className(), 'targetAttribute' => ['hotelId' => 'id']],
            [['supplierId'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplierId' => 'id']],
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
            'motherHotelSupplierId' => Yii::t('app', 'Mother Hotel Supplier ID'),
            'hotelId' => Yii::t('app', 'Hotel ID'),
            'billId' => Yii::t('app', 'Bill ID'),
            'supplierId' => Yii::t('app', 'Supplier ID'),
            'supplierRef' => Yii::t('app', 'Supplier Ref'),
            'issueDate' => Yii::t('app', 'Issue Date'),
            'refundRequestDate' => Yii::t('app', 'Refund Request Date'),
            'type' => Yii::t('app', 'Type'),
            'serviceDetails' => Yii::t('app', 'Service Details'),
            'numberOfNights' => Yii::t('app', 'Number Of Nights'),
            'quantity' => Yii::t('app', 'Quantity'),
            'unitPrice' => Yii::t('app', 'Unit Price'),
            'costOfSale' => Yii::t('app', 'Cost Of Sale'),
            'paidAmount' => Yii::t('app', 'Paid Amount'),
            'paymentStatus' => Yii::t('app', 'Payment Status'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[Bill]].
     *
     * @return ActiveQuery
     */
    public function getBill(): ActiveQuery
    {
        return $this->hasOne(Bill::class, ['id' => 'billId']);
    }

    /**
     * Gets query for [[Hotel]].
     *
     * @return ActiveQuery
     */
    public function getHotel(): ActiveQuery
    {
        return $this->hasOne(Hotel::class, ['id' => 'hotelId']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return ActiveQuery
     */
    public function getSupplier(): ActiveQuery
    {
        return $this->hasOne(Supplier::class, ['id' => 'supplierId']);
    }
}
