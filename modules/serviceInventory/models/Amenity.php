<?php

namespace app\modules\serviceInventory\models;

use Yii;
use app\traits\BehaviorTrait;

/**
 * This is the model class for table "{{%amenity}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property int|null $status
 * @property int $createdAt
 * @property int $createdBy
 * @property int $updatedAt
 * @property int $updatedBy
 *
 * @property HotelInventoryAmenity[] $hotelInventoryAmenities
 */
class Amenity extends \yii\db\ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%amenity}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'createdAt', 'createdBy', 'updatedAt', 'updatedBy'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 255],
            [['uid'], 'unique'],
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
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[HotelInventoryAmenities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHotelInventoryAmenities()
    {
        return $this->hasMany(HotelInventoryAmenity::class, ['amenityId' => 'id']);
    }
}
