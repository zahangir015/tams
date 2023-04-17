<?php

namespace app\modules\sale\models;

use app\components\GlobalConstant;
use app\modules\sale\models\holiday\Holiday;
use app\modules\sale\models\holiday\HolidayRefund;
use app\modules\sale\models\hotel\Hotel;
use app\modules\sale\models\hotel\HotelRefund;
use app\modules\sale\models\ticket\Ticket;
use app\modules\sale\models\ticket\TicketRefund;
use app\modules\sale\models\visa\Visa;
use app\modules\sale\models\visa\VisaRefund;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%customer}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property string $name
 * @property string $company
 * @property string $customerCode
 * @property string $category
 * @property integer $starCategoryId
 * @property string $email
 * @property string|null $address
 * @property string|null $phone
 * @property int|null $creditModality
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 */
class Customer extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%customer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'company', 'customerCode', 'category', 'email'], 'required'],
            [['category'], 'string'],
            [['creditModality', 'starCategoryId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name', 'company', 'email', 'address'], 'string', 'max' => 255],
            [['customerCode'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 50],
            [['uid'], 'unique'],
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
            'name' => Yii::t('app', 'Name'),
            'company' => Yii::t('app', 'Company'),
            'customerCode' => Yii::t('app', 'Customer Code'),
            'category' => Yii::t('app', 'Category'),
            'starCategoryId' => Yii::t('app', 'Star Category'),
            'email' => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Address'),
            'phone' => Yii::t('app', 'Phone'),
            'creditModality' => Yii::t('app', 'Credit Modality'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    public static function query($query): array
    {
        return self::find()
            ->select(['id', 'name', 'company', 'email', 'customerCode'])
            ->where(['like', 'name', $query])
            ->orWhere(['like', 'company', $query])
            ->orWhere(['like', 'customerCode', $query])
            ->orWhere(['like', 'email', $query])
            ->andWhere([self::tableName().'.status' => GlobalConstant::ACTIVE_STATUS])
            ->andWhere([self::tableName().'.agencyId' => Yii::$app->user->identity->agencyId])
            ->all();
    }

    /**
     * Gets query for [[Customer]] from [[Ticket]].
     *
     * @return ActiveQuery
     */
    public function getTickets(): ActiveQuery
    {
        return $this->hasMany(Ticket::class, ['customerId' => 'id']);
    }

    /**
     * Gets query for [[Customer]] from [[Visa]].
     *
     * @return ActiveQuery
     */
    public function getVisas(): ActiveQuery
    {
        return $this->hasMany(Visa::class, ['customerId' => 'id']);
    }

    /**
     * Gets query for [[Customer]] from [[Hotel]].
     *
     * @return ActiveQuery
     */
    public function getHotels(): ActiveQuery
    {
        return $this->hasMany(Hotel::class, ['customerId' => 'id']);
    }

    /**
     * Gets query for [[Customer]] from [[Holiday]].
     *
     * @return ActiveQuery
     */
    public function getHolidays(): ActiveQuery
    {
        return $this->hasMany(Holiday::class, ['customerId' => 'id']);
    }

    /**
     * Gets query for [[Customer]] from [[Ticket]].
     *
     * @return ActiveQuery
     */
    public function getRefundTickets(): ActiveQuery
    {
        return $this->hasMany(TicketRefund::class, ['refId' => 'id'])->onCondition(['refModel' => Customer::class]);
    }

    /**
     * Gets query for [[Customer]] from [[Visa]].
     *
     * @return ActiveQuery
     */
    public function getRefundVisas(): ActiveQuery
    {
        return $this->hasMany(VisaRefund::class, ['refId' => 'id'])->onCondition(['refModel' => Customer::class]);
    }

    /**
     * Gets query for [[Customer]] from [[Hotel]].
     *
     * @return ActiveQuery
     */
    public function getRefundHotels(): ActiveQuery
    {
        return $this->hasMany(HotelRefund::class, ['refId' => 'id'])->onCondition(['refModel' => Customer::class]);
    }

    /**
     * Gets query for [[Customer]] from [[Holiday]].
     *
     * @return ActiveQuery
     */
    public function getRefundHolidays(): ActiveQuery
    {
        return $this->hasMany(HolidayRefund::class, ['refId' => 'id'])->onCondition(['refModel' => Customer::class]);
    }
}
