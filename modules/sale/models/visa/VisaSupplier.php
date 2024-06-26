<?php

namespace app\modules\sale\models\visa;

use app\models\Country;
use app\modules\account\models\Bill;
use app\modules\sale\models\Supplier;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%visa_supplier}}".
 *
 * @property int $id
 * @property string $uid
 * @property int|null $motherVisaSupplierId
 * @property int $visaId
 * @property int|null $billId
 * @property int $countryId
 * @property int $supplierId
 * @property string $supplierRef
 * @property string $paxName
 * @property string $issueDate
 * @property string|null $refundRequestDate
 * @property string $type
 * @property string|null $serviceDetails
 * @property int $quantity
 * @property float $unitPrice
 * @property float $costOfSale
 * @property float|null $securityDeposit
 * @property float|null $paidAmount
 * @property string|null $paymentStatus
 * @property int|null $status
 *
 * @property Bill $bill
 * @property Supplier $supplier
 * @property Visa $visa
 */
class VisaSupplier extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%visa_supplier}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'visaId', 'countryId', 'supplierId', 'supplierRef', 'paxName', 'issueDate', 'type', 'quantity', 'unitPrice', 'costOfSale'], 'required'],
            [['motherVisaSupplierId', 'visaId', 'billId', 'countryId', 'supplierId', 'quantity', 'status'], 'integer'],
            [['paxName', 'issueDate', 'refundRequestDate'], 'safe'],
            [['type', 'paymentStatus'], 'string'],
            [['unitPrice', 'costOfSale', 'securityDeposit', 'paidAmount'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['supplierRef', 'serviceDetails'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['billId'], 'exist', 'skipOnError' => true, 'targetClass' => Bill::class, 'targetAttribute' => ['billId' => 'id']],
            [['supplierId'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplierId' => 'id']],
            [['visaId'], 'exist', 'skipOnError' => true, 'targetClass' => Visa::class, 'targetAttribute' => ['visaId' => 'id']],
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
            'motherVisaSupplierId' => Yii::t('app', 'Mother Visa Supplier ID'),
            'visaId' => Yii::t('app', 'Visa'),
            'billId' => Yii::t('app', 'Bill'),
            'countryId' => Yii::t('app', 'Country'),
            'supplierId' => Yii::t('app', 'Supplier'),
            'supplierRef' => Yii::t('app', 'Supplier Ref'),
            'paxName' => Yii::t('app', 'Pax Name'),
            'issueDate' => Yii::t('app', 'Issue Date'),
            'refundRequestDate' => Yii::t('app', 'Refund Request Date'),
            'type' => Yii::t('app', 'Type'),
            'serviceDetails' => Yii::t('app', 'Service Details'),
            'quantity' => Yii::t('app', 'Quantity'),
            'unitPrice' => Yii::t('app', 'Unit Price'),
            'costOfSale' => Yii::t('app', 'Total cost of sale'),
            'securityDeposit' => Yii::t('app', 'Security Deposit'),
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
     * Gets query for [[Supplier]].
     *
     * @return ActiveQuery
     */
    public function getSupplier(): ActiveQuery
    {
        return $this->hasOne(Supplier::class, ['id' => 'supplierId']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'countryId']);
    }

    /**
     * Gets query for [[Visa]].
     *
     * @return ActiveQuery
     */
    public function getVisa(): ActiveQuery
    {
        return $this->hasOne(Visa::class, ['id' => 'visaId']);
    }
}
