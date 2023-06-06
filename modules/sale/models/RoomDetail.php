<?php

namespace app\modules\sale\models;

use Yii;

/**
 * This is the model class for table "{{%room_detail}}".
 *
 * @property int $id
 * @property int $hotelProposalId
 * @property int $roomTypeId
 * @property int $numberOfRoom
 * @property int $numberOfNight
 * @property float $perNightPrice
 * @property int|null $extraBed
 * @property int|null $breakfast
 * @property string|null $checkIn
 * @property string|null $checkOut
 *
 * @property HotelProposal $hotelProposal
 * @property RoomType $roomType
 */
class RoomDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%room_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hotelProposalId', 'roomTypeId', 'numberOfRoom', 'numberOfNight', 'perNightPrice'], 'required'],
            [['hotelProposalId', 'roomTypeId', 'numberOfRoom', 'numberOfNight', 'extraBed', 'breakfast'], 'integer'],
            [['perNightPrice'], 'number'],
            [['checkIn', 'checkOut'], 'safe'],
            [['hotelProposalId'], 'exist', 'skipOnError' => true, 'targetClass' => HotelProposal::class, 'targetAttribute' => ['hotelProposalId' => 'id']],
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
            'hotelProposalId' => Yii::t('app', 'Hotel Proposal ID'),
            'roomTypeId' => Yii::t('app', 'Room Type ID'),
            'numberOfRoom' => Yii::t('app', 'Number Of Room'),
            'numberOfNight' => Yii::t('app', 'Number Of Night'),
            'perNightPrice' => Yii::t('app', 'Per Night Price'),
            'extraBed' => Yii::t('app', 'Extra Bed'),
            'breakfast' => Yii::t('app', 'Breakfast'),
            'checkIn' => Yii::t('app', 'Check In'),
            'checkOut' => Yii::t('app', 'Check Out'),
        ];
    }

    /**
     * Gets query for [[HotelProposal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHotelProposal()
    {
        return $this->hasOne(HotelProposal::class, ['id' => 'hotelProposalId']);
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
