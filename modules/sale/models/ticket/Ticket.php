<?php

namespace app\modules\sale\models\ticket;

use app\components\GlobalConstant;
use app\modules\account\models\Invoice;
use app\modules\sale\models\Airline;
use app\modules\sale\models\Customer;
use app\modules\sale\models\Provider;
use app\modules\sale\models\TicketRefund;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%ticket}}".
 *
 * @property int $id
 * @property string $uid
 * @property int|null $motherTicketId
 * @property int $airlineId
 * @property int $providerId
 * @property int|null $invoiceId
 * @property int $customerId
 * @property string $customerCategory
 * @property string $paxName
 * @property string|null $paxType
 * @property string $eTicket
 * @property string $pnrCode
 * @property string $type
 * @property string|null $tripType
 * @property int|null $bookedOnline
 * @property int|null $flightType
 * @property string $seatClass
 * @property int|null $codeShare
 * @property string|null $reference
 * @property string $issueDate
 * @property string|null $departureDate
 * @property string|null $refundRequestDate
 * @property string|null $route
 * @property int|null $numberOfSegment
 * @property float|null $baseFare
 * @property float|null $tax
 * @property float|null $otherTax
 * @property float|null $commission
 * @property float|null $commissionReceived
 * @property float|null $incentive
 * @property float|null $incentiveReceived
 * @property float|null $govTax
 * @property float|null $serviceCharge
 * @property float|null $ait
 * @property float|null $discount
 * @property float|null $quoteAmount
 * @property float|null $receivedAmount
 * @property string|null $paymentStatus
 * @property float|null $costOfSale
 * @property float|null $netProfit
 * @property string|null $baggage
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Airline $airline
 * @property Customer $customer
 * @property Invoice $invoice
 * @property Provider $provider
 * @property TicketRefund[] $ticketRefunds
 * @property TicketSupplier[] $ticketSuppliers
 */
class Ticket extends ActiveRecord
{
    use BehaviorTrait;
    public $csv;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['airlineId', 'providerId', 'customerId', 'customerCategory', 'paxName', 'eTicket', 'pnrCode', 'type', 'seatClass', 'issueDate'], 'required'],
            [['motherTicketId', 'airlineId', 'providerId', 'invoiceId', 'customerId', 'bookedOnline', 'flightType', 'codeShare', 'numberOfSegment', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['eTicket', 'airlineId', 'type'], 'unique', 'targetAttribute' => ['eTicket', 'airlineId', 'type'], 'on' => 'create'],
            [['type', 'tripType', 'paymentStatus'], 'string'],
            [['issueDate', 'departureDate', 'refundRequestDate'], 'safe'],
            [['baseFare', 'tax', 'otherTax', 'commission', 'commissionReceived', 'incentive', 'incentiveReceived', 'govTax', 'serviceCharge', 'ait', 'quoteAmount', 'receivedAmount', 'costOfSale', 'netProfit', 'discount'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['customerCategory'], 'string', 'max' => 10],
            [['paxName'], 'string', 'max' => 120],
            [['paxType'], 'string', 'max' => 1],
            [['eTicket', 'pnrCode'], 'string', 'max' => 50],
            [['seatClass', 'reference', 'route', 'baggage'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['airlineId'], 'exist', 'skipOnError' => true, 'targetClass' => Airline::className(), 'targetAttribute' => ['airlineId' => 'id']],
            [['customerId'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customerId' => 'id']],
            [['invoiceId'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoiceId' => 'id']],
            [['providerId'], 'exist', 'skipOnError' => true, 'targetClass' => Provider::className(), 'targetAttribute' => ['providerId' => 'id']],
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
            'motherTicketId' => Yii::t('app', 'Mother Ticket ID'),
            'airlineId' => Yii::t('app', 'Airline ID'),
            'providerId' => Yii::t('app', 'Provider ID'),
            'invoiceId' => Yii::t('app', 'Invoice ID'),
            'customerId' => Yii::t('app', 'Customer ID'),
            'customerCategory' => Yii::t('app', 'Customer Category'),
            'paxName' => Yii::t('app', 'Pax Name'),
            'paxType' => Yii::t('app', 'Pax Type'),
            'eTicket' => Yii::t('app', 'E Ticket'),
            'pnrCode' => Yii::t('app', 'Pnr Code'),
            'type' => Yii::t('app', 'Type'),
            'tripType' => Yii::t('app', 'Trip Type'),
            'bookedOnline' => Yii::t('app', 'Booked Online'),
            'flightType' => Yii::t('app', 'Flight Type'),
            'seatClass' => Yii::t('app', 'Seat Class'),
            'codeShare' => Yii::t('app', 'Code Share'),
            'reference' => Yii::t('app', 'Reference'),
            'issueDate' => Yii::t('app', 'Issue Date'),
            'departureDate' => Yii::t('app', 'Departure Date'),
            'refundRequestDate' => Yii::t('app', 'Refund Request Date'),
            'route' => Yii::t('app', 'Route'),
            'numberOfSegment' => Yii::t('app', 'Number Of Segment'),
            'baseFare' => Yii::t('app', 'Base Fare'),
            'tax' => Yii::t('app', 'Tax'),
            'otherTax' => Yii::t('app', 'Other Tax'),
            'commission' => Yii::t('app', 'Commission'),
            'commissionReceived' => Yii::t('app', 'Commission Received'),
            'incentive' => Yii::t('app', 'Incentive'),
            'incentiveReceived' => Yii::t('app', 'Incentive Received'),
            'govTax' => Yii::t('app', 'Gov Tax'),
            'serviceCharge' => Yii::t('app', 'Service Charge'),
            'ait' => Yii::t('app', 'Ait'),
            'discount' => Yii::t('app', 'Discount'),
            'quoteAmount' => Yii::t('app', 'Quote Amount'),
            'receivedAmount' => Yii::t('app', 'Received Amount'),
            'paymentStatus' => Yii::t('app', 'Payment Status'),
            'costOfSale' => Yii::t('app', 'Cost Of Sale'),
            'netProfit' => Yii::t('app', 'Net Profit'),
            'baggage' => Yii::t('app', 'Baggage'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Airline]].
     *
     * @return ActiveQuery
     */
    public function getAirline(): ActiveQuery
    {
        return $this->hasOne(Airline::className(), ['id' => 'airlineId']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return ActiveQuery
     */
    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(Customer::className(), ['id' => 'customerId']);
    }

    /**
     * Gets query for [[Invoice]].
     *
     * @return ActiveQuery
     */
    public function getInvoice(): ActiveQuery
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoiceId']);
    }

    /**
     * Gets query for [[Provider]].
     *
     * @return ActiveQuery
     */
    public function getProvider(): ActiveQuery
    {
        return $this->hasOne(Provider::className(), ['id' => 'providerId']);
    }

    /**
     * Gets query for [[TicketRefunds]].
     *
     * @return ActiveQuery
     */
    public function getTicketRefunds(): ActiveQuery
    {
        return $this->hasMany(TicketRefund::className(), ['ticketId' => 'id']);
    }

    /**
     * Gets query for [[TicketSuppliers]].
     *
     * @return ActiveQuery
     */
    public function getTicketSuppliers(): ActiveQuery
    {
        return $this->hasMany(TicketSupplier::className(), ['ticketId' => 'id']);
    }

    public static function query($query): array
    {
        return self::find()
            ->select(['id', 'eTicket', 'pnrCode'])
            ->where(['like', 'eTicket', $query])
            ->orWhere(['like', 'pnrCode', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}
