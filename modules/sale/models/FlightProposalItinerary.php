<?php

namespace app\modules\sale\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%flight_proposal_itinerary}}".
 *
 * @property int $id
 * @property int $flightProposalId
 * @property string $flightNumber
 * @property string $departureFrom
 * @property string $departure
 * @property string $arrivalTo
 * @property string $arrival
 *
 * @property FlightProposal $flightProposal
 */
class FlightProposalItinerary extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%flight_proposal_itinerary}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['flightProposalId', 'flightNumber', 'departureFrom', 'departure', 'arrivalTo', 'arrival'], 'required'],
            [['flightProposalId'], 'integer'],
            [['departure', 'arrival'], 'safe'],
            [['flightNumber'], 'string', 'max' => 100],
            [['departureFrom', 'arrivalTo'], 'string', 'max' => 3],
            [['flightProposalId'], 'exist', 'skipOnError' => true, 'targetClass' => FlightProposal::class, 'targetAttribute' => ['flightProposalId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'flightProposalId' => Yii::t('app', 'Flight Proposal ID'),
            'flightNumber' => Yii::t('app', 'Flight Number'),
            'departureFrom' => Yii::t('app', 'Departure From'),
            'departure' => Yii::t('app', 'Departure'),
            'arrivalTo' => Yii::t('app', 'Arrival To'),
            'arrival' => Yii::t('app', 'Arrival'),
        ];
    }

    /**
     * Gets query for [[FlightProposal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlightProposal()
    {
        return $this->hasOne(FlightProposal::class, ['id' => 'flightProposalId']);
    }
}
