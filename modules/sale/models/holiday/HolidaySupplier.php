<?php

namespace app\modules\sale\models\holiday;

use app\modules\account\models\Bill;
use app\modules\sale\models\Supplier;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%holiday_supplier}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $holidayId
 * @property int|null $billId
 * @property int $supplierId
 * @property string $supplierRef
 * @property string $issueDate
 * @property string $departureDate
 * @property string|null $refundRequestDate
 * @property string $type
 * @property string|null $serviceDetails
 * @property int $quantity
 * @property int $unitPrice
 * @property float $costOfSale
 * @property float|null $paidAmount
 * @property string|null $paymentStatus
 * @property int|null $status
 * @property string|null $description
 * @property int $holidayCategoryId
 *
 * @property Bill $bill
 * @property Holiday $holiday
 * @property Supplier $supplier
 */
class HolidaySupplier extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%holiday_supplier}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'holidayId', 'supplierId', 'supplierRef', 'issueDate', 'departureDate', 'type', 'quantity', 'unitPrice', 'costOfSale', 'holidayCategoryId'], 'required'],
            [['holidayId', 'billId', 'supplierId', 'quantity', 'unitPrice', 'status', 'holidayCategoryId'], 'integer'],
            [['issueDate', 'departureDate', 'refundRequestDate', 'description'], 'safe'],
            [['type', 'paymentStatus'], 'string'],
            [['costOfSale', 'paidAmount'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['supplierRef', 'serviceDetails'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['billId'], 'exist', 'skipOnError' => true, 'targetClass' => Bill::className(), 'targetAttribute' => ['billId' => 'id']],
            [['holidayId'], 'exist', 'skipOnError' => true, 'targetClass' => Holiday::className(), 'targetAttribute' => ['holidayId' => 'id']],
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
            'holidayId' => Yii::t('app', 'Holiday ID'),
            'billId' => Yii::t('app', 'Bill ID'),
            'supplierId' => Yii::t('app', 'Supplier ID'),
            'supplierRef' => Yii::t('app', 'Supplier Ref'),
            'issueDate' => Yii::t('app', 'Issue Date'),
            'departureDate' => Yii::t('app', 'Departure Date'),
            'refundRequestDate' => Yii::t('app', 'Refund Request Date'),
            'type' => Yii::t('app', 'Type'),
            'serviceDetails' => Yii::t('app', 'Service Details'),
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
        return $this->hasOne(Bill::className(), ['id' => 'billId']);
    }

    /**
     * Gets query for [[Holiday]].
     *
     * @return ActiveQuery
     */
    public function getHoliday(): ActiveQuery
    {
        return $this->hasOne(Holiday::className(), ['id' => 'holidayId']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return ActiveQuery
     */
    public function getSupplier(): ActiveQuery
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplierId']);
    }
}
