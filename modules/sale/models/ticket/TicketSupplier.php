<?php

namespace app\modules\sale\models\ticket;

use app\modules\account\models\Bill;
use app\modules\sale\models\Airline;
use app\modules\sale\models\Supplier;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%ticket_supplier}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $ticketId
 * @property int $supplierId
 * @property int $airlineId
 * @property int|null $billId
 * @property int $issueDate
 * @property string|null $refundRequestDate
 * @property string $eTicket
 * @property string $pnrCode
 * @property string $type
 * @property float|null $baseFare
 * @property float|null $tax
 * @property float|null $otherTax
 * @property float $serviceCharge
 * @property float $costOfSale
 * @property float|null $paidAmount
 * @property string|null $paymentStatus
 * @property int $status
 *
 * @property Airline $airline
 * @property Bill $bill
 * @property Supplier $supplier
 * @property Ticket $ticket
 */
class TicketSupplier extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%ticket_supplier}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['ticketId', 'supplierId', 'airlineId', 'issueDate', 'eTicket', 'pnrCode', 'type', 'issueDate', 'costOfSale'], 'required'],
            [['ticketId', 'supplierId', 'airlineId', 'billId', 'status'], 'integer'],
            [['refundRequestDate'], 'safe'],
            [['type', 'paymentStatus'], 'string'],
            [['baseFare', 'tax', 'otherTax', 'costOfSale', 'paidAmount', 'serviceCharge'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['eTicket', 'pnrCode'], 'string', 'max' => 50],
            [['uid'], 'unique'],
            [['airlineId'], 'exist', 'skipOnError' => true, 'targetClass' => Airline::className(), 'targetAttribute' => ['airlineId' => 'id']],
            [['billId'], 'exist', 'skipOnError' => true, 'targetClass' => Bill::className(), 'targetAttribute' => ['billId' => 'id']],
            [['supplierId'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplierId' => 'id']],
            [['ticketId'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::className(), 'targetAttribute' => ['ticketId' => 'id']],
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
            'ticketId' => Yii::t('app', 'Ticket ID'),
            'supplierId' => Yii::t('app', 'Supplier ID'),
            'airlineId' => Yii::t('app', 'Airline ID'),
            'billId' => Yii::t('app', 'Bill ID'),
            'issueDate' => Yii::t('app', 'Issue Date'),
            'refundRequestDate' => Yii::t('app', 'Refund Request Date'),
            'eTicket' => Yii::t('app', 'E Ticket'),
            'pnrCode' => Yii::t('app', 'Pnr Code'),
            'type' => Yii::t('app', 'Type'),
            'baseFare' => Yii::t('app', 'Base Fare'),
            'tax' => Yii::t('app', 'Tax'),
            'otherTax' => Yii::t('app', 'Other Tax'),
            'costOfSale' => Yii::t('app', 'Cost Of Sale'),
            'paidAmount' => Yii::t('app', 'Paid Amount'),
            'paymentStatus' => Yii::t('app', 'Payment Status'),
            'status' => Yii::t('app', 'Status'),
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
     * Gets query for [[Bill]].
     *
     * @return ActiveQuery
     */
    public function getBill(): ActiveQuery
    {
        return $this->hasOne(Bill::className(), ['id' => 'billId']);
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

    /**
     * Gets query for [[Ticket]].
     *
     * @return ActiveQuery
     */
    public function getTicket(): ActiveQuery
    {
        return $this->hasOne(Ticket::className(), ['id' => 'ticketId']);
    }
}
