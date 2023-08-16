<?php

namespace app\modules\account\models;

use app\components\GlobalConstant;
use app\modules\sale\models\holiday\HolidaySupplier;
use app\modules\sale\models\hotel\HotelSupplier;
use app\modules\sale\models\Supplier;
use app\modules\sale\models\ticket\TicketSupplier;
use app\modules\sale\models\visa\VisaSupplier;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%bill}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property int $supplierId
 * @property string $billNumber
 * @property string $date
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
 * @property Supplier $supplier
 * @property TicketSupplier[] $ticketSuppliers
 */
class Bill extends ActiveRecord
{
    use BehaviorTrait;
    public $dateRange;
    public $billFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%bill}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'supplierId', 'billNumber', 'date'], 'required'],
            [['supplierId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['date'], 'safe'],
            [['paidAmount', 'dueAmount', 'discountedAmount', 'refundAdjustmentAmount'], 'number'],
            [['remarks'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['billNumber'], 'string', 'max' => 64],
            [['uid'], 'unique'],
            [['billNumber'], 'unique'],
            [['supplierId'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplierId' => 'id']],
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
            'supplierId' => Yii::t('app', 'Supplier ID'),
            'billNumber' => Yii::t('app', 'Bill Number'),
            'date' => Yii::t('app', 'Date'),
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
     * Gets query for [[Supplier]].
     *
     * @return ActiveQuery
     */
    public function getSupplier(): ActiveQuery
    {
        return $this->hasOne(Supplier::class, ['id' => 'supplierId']);
    }

    /**
     * Gets query for [[TicketSuppliers]].
     *
     * @return ActiveQuery
     */
    public function getTicketSuppliers(): ActiveQuery
    {
        return $this->hasMany(TicketSupplier::class, ['billId' => 'id']);
    }
    /**
     * Gets query for [[HolidaySupplier]].
     *
     * @return ActiveQuery
     */
    public function getHolidaySuppliers(): ActiveQuery
    {
        return $this->hasMany(HolidaySupplier::class, ['billId' => 'id']);
    }
    /**
     * Gets query for [[HotelSupplier]].
     *
     * @return ActiveQuery
     */
    public function getHotelSuppliers(): ActiveQuery
    {
        return $this->hasMany(HotelSupplier::class, ['billId' => 'id']);
    }
    /**
     * Gets query for [[VisaSupplier]].
     *
     * @return ActiveQuery
     */
    public function getVisaSuppliers(): ActiveQuery
    {
        return $this->hasMany(VisaSupplier::class, ['billId' => 'id']);
    }
    /**
     * Gets query for [[TicketSuppliers]].
     *
     * @return ActiveQuery
     */
    public function getDetails(): ActiveQuery
    {
        return $this->hasMany(BillDetail::class, ['billId' => 'id']);
    }

    /**
     * Gets query for [[InvoiceDetail]].
     *
     * @return ActiveQuery
     */
    public function getTransactions(): ActiveQuery
    {
        return $this->hasMany(Transaction::class, ['refId' => 'id'])->onCondition([Transaction::tableName() . '.refModel' => 'app\\modules\\account\\models\\Bill', Transaction::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS, Transaction::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId]);
    }
}
