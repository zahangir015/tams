<?php

namespace app\modules\serviceInventory\models;

use Yii;
use app\traits\BehaviorTrait;

/**
 * This is the model class for table "{{%hotel_inventory}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $supplierId
 * @property string $hotelName
 * @property string $hotelAddress
 * @property int $countryId
 * @property int $cityId
 * @property int $hotelCategoryId
 * @property int|null $status
 * @property int $createdAt
 * @property int $createdBy
 * @property int $updatedAt
 * @property int $updatedBy
 *
 * @property City $city
 * @property Country $country
 * @property HotelCategory $hotelCategory
 * @property HotelInventoryAmenity[] $hotelInventoryAmenities
 * @property HotelSupplier $supplier
 */
class HotelInventory extends \yii\db\ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hotel_inventory}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'supplierId', 'hotelName', 'hotelAddress', 'countryId', 'cityId', 'hotelCategoryId', 'createdAt', 'createdBy', 'updatedAt', 'updatedBy'], 'required'],
            [['supplierId', 'countryId', 'cityId', 'hotelCategoryId', 'status', 'createdAt', 'createdBy', 'updatedAt', 'updatedBy'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['hotelName', 'hotelAddress'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['cityId'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['cityId' => 'id']],
            [['countryId'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['countryId' => 'id']],
            [['hotelCategoryId'], 'exist', 'skipOnError' => true, 'targetClass' => HotelCategory::class, 'targetAttribute' => ['hotelCategoryId' => 'id']],
            [['supplierId'], 'exist', 'skipOnError' => true, 'targetClass' => HotelSupplier::class, 'targetAttribute' => ['supplierId' => 'supplierId']],
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
            'supplierId' => Yii::t('app', 'Supplier ID'),
            'hotelName' => Yii::t('app', 'Hotel Name'),
            'hotelAddress' => Yii::t('app', 'Hotel Address'),
            'countryId' => Yii::t('app', 'Country ID'),
            'cityId' => Yii::t('app', 'City ID'),
            'hotelCategoryId' => Yii::t('app', 'Hotel Category ID'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'cityId']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'countryId']);
    }

    /**
     * Gets query for [[HotelCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHotelCategory()
    {
        return $this->hasOne(HotelCategory::class, ['id' => 'hotelCategoryId']);
    }

    /**
     * Gets query for [[HotelInventoryAmenities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHotelInventoryAmenities()
    {
        return $this->hasMany(HotelInventoryAmenity::class, ['hotelInventoryId' => 'id']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(HotelSupplier::class, ['supplierId' => 'supplierId']);
    }
}
