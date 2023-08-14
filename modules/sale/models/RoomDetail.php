<?php

namespace app\modules\sale\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
class RoomDetail extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%room_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
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
    public function attributeLabels(): array
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
     * @return ActiveQuery
     */
    public function getHotelProposal(): ActiveQuery
    {
        return $this->hasOne(HotelProposal::class, ['id' => 'hotelProposalId']);
    }

    /**
     * Gets query for [[RoomType]].
     *
     * @return ActiveQuery
     */
    public function getRoomType(): ActiveQuery
    {
        return $this->hasOne(RoomType::class, ['id' => 'roomTypeId']);
    }
}
