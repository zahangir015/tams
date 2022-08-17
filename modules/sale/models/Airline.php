<?php

namespace app\modules\sale\models;

use TimestampTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%airline}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $supplierId
 * @property string $code
 * @property string $name
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
 * @property AirlineHistory[] $airlineHistories
 * @property Supplier $supplier
 */
class Airline extends \yii\db\ActiveRecord
{
    use TimestampTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%airline}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'supplierId', 'code', 'name', 'createdBy', 'createdAt'], 'required'],
            [['supplierId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['commission', 'incentive', 'govTax', 'serviceCharge'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['code'], 'unique'],
            [['supplierId'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplierId' => 'id']],
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
            'supplierId' => Yii::t('app', 'Supplier'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
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
     * Gets query for [[AirlineHistories]].
     *
     * @return ActiveQuery
     */
    public function getAirlineHistories()
    {
        return $this->hasMany(AirlineHistory::className(), ['airlineId' => 'id']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplierId']);
    }
}
