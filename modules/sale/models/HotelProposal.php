<?php

namespace app\modules\sale\models;

use Yii;

/**
 * This is the model class for table "{{%hotel_proposal}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property int $hotelCategoryId
 * @property string $hotelName
 * @property string $hotelAddress
 * @property int $countryId
 * @property int $cityId
 * @property int $numberOfAdult
 * @property int $numberOfChild
 * @property string|null $amenities
 * @property float $totalPrice
 * @property float $discount
 * @property string|null $notes
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Agency $agency
 * @property City $city
 * @property Country $country
 * @property HotelCategory $hotelCategory
 * @property RoomDetail[] $roomDetails
 */
class HotelProposal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hotel_proposal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'agencyId', 'hotelCategoryId', 'hotelName', 'hotelAddress', 'countryId', 'cityId', 'numberOfAdult', 'createdBy', 'createdAt'], 'required'],
            [['agencyId', 'hotelCategoryId', 'countryId', 'cityId', 'numberOfAdult', 'numberOfChild', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['amenities', 'notes'], 'string'],
            [['totalPrice', 'discount'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['hotelName', 'hotelAddress'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['agencyId'], 'exist', 'skipOnError' => true, 'targetClass' => Agency::class, 'targetAttribute' => ['agencyId' => 'id']],
            [['cityId'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['cityId' => 'id']],
            [['countryId'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['countryId' => 'id']],
            [['hotelCategoryId'], 'exist', 'skipOnError' => true, 'targetClass' => HotelCategory::class, 'targetAttribute' => ['hotelCategoryId' => 'id']],
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
            'agencyId' => Yii::t('app', 'Agency ID'),
            'hotelCategoryId' => Yii::t('app', 'Hotel Category ID'),
            'hotelName' => Yii::t('app', 'Hotel Name'),
            'hotelAddress' => Yii::t('app', 'Hotel Address'),
            'countryId' => Yii::t('app', 'Country ID'),
            'cityId' => Yii::t('app', 'City ID'),
            'numberOfAdult' => Yii::t('app', 'Number Of Adult'),
            'numberOfChild' => Yii::t('app', 'Number Of Child'),
            'amenities' => Yii::t('app', 'Amenities'),
            'totalPrice' => Yii::t('app', 'Total Price'),
            'discount' => Yii::t('app', 'Discount'),
            'notes' => Yii::t('app', 'Notes'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Agency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgency()
    {
        return $this->hasOne(Agency::class, ['id' => 'agencyId']);
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
     * Gets query for [[RoomDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomDetails()
    {
        return $this->hasMany(RoomDetail::class, ['hotelProposalId' => 'id']);
    }
}
