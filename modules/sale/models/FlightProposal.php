<?php

namespace app\modules\sale\models;

use Yii;

/**
 * This is the model class for table "{{%flight_proposal}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property int $airlineId
 * @property string $class
 * @property int $tripType
 * @property string $route
 * @property string $departure
 * @property string $arrival
 * @property int $numberOfAdult
 * @property float $pricePerAdult
 * @property string|null $baggagePerAdult
 * @property int $numberOfChild
 * @property float $pricePerChild
 * @property string|null $baggagePerChild
 * @property int $numberOfInfant
 * @property float $pricePerInfant
 * @property string|null $baggagePerInfant
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
 * @property Airline $airline
 * @property FlightProposalItinerary[] $flightProposalItineraries
 */
class FlightProposal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%flight_proposal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'agencyId', 'airlineId', 'class', 'tripType', 'route', 'departure', 'arrival', 'numberOfAdult', 'createdBy', 'createdAt'], 'required'],
            [['agencyId', 'airlineId', 'tripType', 'numberOfAdult', 'numberOfChild', 'numberOfInfant', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['departure', 'arrival'], 'safe'],
            [['pricePerAdult', 'pricePerChild', 'pricePerInfant', 'totalPrice', 'discount'], 'number'],
            [['notes'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['class', 'route'], 'string', 'max' => 120],
            [['baggagePerAdult', 'baggagePerChild', 'baggagePerInfant'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['agencyId'], 'exist', 'skipOnError' => true, 'targetClass' => Agency::class, 'targetAttribute' => ['agencyId' => 'id']],
            [['airlineId'], 'exist', 'skipOnError' => true, 'targetClass' => Airline::class, 'targetAttribute' => ['airlineId' => 'id']],
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
            'airlineId' => Yii::t('app', 'Airline ID'),
            'class' => Yii::t('app', 'Class'),
            'tripType' => Yii::t('app', 'Trip Type'),
            'route' => Yii::t('app', 'Route'),
            'departure' => Yii::t('app', 'Departure'),
            'arrival' => Yii::t('app', 'Arrival'),
            'numberOfAdult' => Yii::t('app', 'Number Of Adult'),
            'pricePerAdult' => Yii::t('app', 'Price Per Adult'),
            'baggagePerAdult' => Yii::t('app', 'Baggage Per Adult'),
            'numberOfChild' => Yii::t('app', 'Number Of Child'),
            'pricePerChild' => Yii::t('app', 'Price Per Child'),
            'baggagePerChild' => Yii::t('app', 'Baggage Per Child'),
            'numberOfInfant' => Yii::t('app', 'Number Of Infant'),
            'pricePerInfant' => Yii::t('app', 'Price Per Infant'),
            'baggagePerInfant' => Yii::t('app', 'Baggage Per Infant'),
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
     * Gets query for [[Airline]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAirline()
    {
        return $this->hasOne(Airline::class, ['id' => 'airlineId']);
    }

    /**
     * Gets query for [[FlightProposalItineraries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlightProposalItineraries()
    {
        return $this->hasMany(FlightProposalItinerary::class, ['flightProposalId' => 'id']);
    }
}
