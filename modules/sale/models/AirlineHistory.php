<?php

namespace app\modules\sale\models;

use TimestampTrait;
use Yii;

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
class AirlineHistory extends \yii\db\ActiveRecord
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
            [['uid', 'airlineId', 'createdBy', 'createdAt'], 'required'],
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
            'airlineId' => Yii::t('app', 'Airline ID'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getAirline()
    {
        return $this->hasOne(Airline::className(), ['id' => 'airlineId']);
    }
}
