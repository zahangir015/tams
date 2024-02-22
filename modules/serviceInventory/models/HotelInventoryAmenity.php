<?php

namespace app\modules\serviceInventory\models;

use Yii;
use app\traits\BehaviorTrait;

/**
 * This is the model class for table "{{%hotel_inventory_amenity}}".
 *
 * @property int $id
 * @property int $hotelInventoryId
 * @property int $amenityId
 *
 * @property Amenity $amenity
 * @property HotelInventory $hotelInventory
 */
class HotelInventoryAmenity extends \yii\db\ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hotel_inventory_amenity}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hotelInventoryId', 'amenityId'], 'required'],
            [['hotelInventoryId', 'amenityId'], 'integer'],
            [['amenityId'], 'exist', 'skipOnError' => true, 'targetClass' => Amenity::class, 'targetAttribute' => ['amenityId' => 'id']],
            [['hotelInventoryId'], 'exist', 'skipOnError' => true, 'targetClass' => HotelInventory::class, 'targetAttribute' => ['hotelInventoryId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hotelInventoryId' => Yii::t('app', 'Hotel Inventory ID'),
            'amenityId' => Yii::t('app', 'Amenity ID'),
        ];
    }

    /**
     * Gets query for [[Amenity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAmenity()
    {
        return $this->hasOne(Amenity::class, ['id' => 'amenityId']);
    }

    /**
     * Gets query for [[HotelInventory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHotelInventory()
    {
        return $this->hasOne(HotelInventory::class, ['id' => 'hotelInventoryId']);
    }
}
