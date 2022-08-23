<?php

namespace app\modules\sale\models;

use app\components\Helper;
use app\traits\TimestampTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%airline_history}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $airlineId
 * @property float|null $commission
 * @property float|null $incentive
 * @property float|null $govTax
 * @property float|null $serviceCharge
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Airline $airline
 */
class AirlineHistory extends ActiveRecord
{
    use TimestampTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%airline_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['airlineId', 'createdBy'], 'required'],
            [['airlineId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['commission', 'incentive', 'govTax', 'serviceCharge'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['uid'], 'unique'],
            [['airlineId'], 'exist', 'skipOnError' => true, 'targetClass' => Airline::className(), 'targetAttribute' => ['airlineId' => 'id']],
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
            'airlineId' => Yii::t('app', 'Airline'),
            'commission' => Yii::t('app', 'Commission'),
            'incentive' => Yii::t('app', 'Incentive'),
            'govTax' => Yii::t('app', 'Gov Tax'),
            'serviceCharge' => Yii::t('app', 'Service Charge'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Airline]].
     *
     * @return ActiveQuery
     */
    public function getAirline(): ActiveQuery
    {
        return $this->hasOne(Airline::className(), ['id' => 'airlineId']);
    }

    public static function store(Airline $airline): array
    {
        $history = new self();
        $historyColumn = $airline->getAttributes(['commission', 'incentive', 'govTax', 'serviceCharge'], ['id', 'uid', 'supplierId', 'code', 'name', 'status', 'createdAt', 'createdBy', 'updatedAt', 'updatedBy']);
        $mergedArray = array_merge($historyColumn, ['airlineId' => $airline->id]);
        $history->load(['AirlineHistory' => $mergedArray]);
        if (!$history->save()) {
            return ['error' => true, 'message' => Helper::processErrorMessages($history->getErrors())];
        }
        return ['error' => false, 'message' => 'History stored successfully'];
    }
}
