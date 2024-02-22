<?php

namespace app\modules\serviceInventory\models;

use Yii;
use app\traits\BehaviorTrait;

/**
 * This is the model class for table "{{%hotel_inventory_room_detail}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $roomTypeId
 * @property int|null $meal
 * @property int|null $extraBed
 * @property int $numberOfRoom
 * @property int|null $isAvailable
 * @property int|null $cancelationPolicy
 * @property float $perNightCost
 * @property string $currency
 * @property int $perNightSelling
 * @property string $priceValidity
 * @property int|null $transfer
 * @property string $transferDetails
 *
 * @property RoomType $roomType
 */
class HotelInventoryRoomDetail extends \yii\db\ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hotel_inventory_room_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'roomTypeId', 'numberOfRoom', 'perNightCost', 'currency', 'perNightSelling', 'priceValidity', 'transferDetails'], 'required'],
            [['roomTypeId', 'meal', 'extraBed', 'numberOfRoom', 'isAvailable', 'cancelationPolicy', 'perNightSelling', 'transfer'], 'integer'],
            [['perNightCost'], 'number'],
            [['priceValidity'], 'safe'],
            [['uid'], 'string', 'max' => 36],
            [['currency'], 'string', 'max' => 3],
            [['transferDetails'], 'string', 'max' => 1024],
            [['uid'], 'unique'],
            [['roomTypeId'], 'exist', 'skipOnError' => true, 'targetClass' => RoomType::class, 'targetAttribute' => ['roomTypeId' => 'id']],
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
            'roomTypeId' => Yii::t('app', 'Room Type ID'),
            'meal' => Yii::t('app', 'Meal'),
            'extraBed' => Yii::t('app', 'Extra Bed'),
            'numberOfRoom' => Yii::t('app', 'Number Of Room'),
            'isAvailable' => Yii::t('app', 'Is Available'),
            'cancelationPolicy' => Yii::t('app', 'Cancelation Policy'),
            'perNightCost' => Yii::t('app', 'Per Night Cost'),
            'currency' => Yii::t('app', 'Currency'),
            'perNightSelling' => Yii::t('app', 'Per Night Selling'),
            'priceValidity' => Yii::t('app', 'Price Validity'),
            'transfer' => Yii::t('app', 'Transfer'),
            'transferDetails' => Yii::t('app', 'Transfer Details'),
        ];
    }

    /**
     * Gets query for [[RoomType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomType()
    {
        return $this->hasOne(RoomType::class, ['id' => 'roomTypeId']);
    }
}
