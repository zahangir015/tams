<?php

namespace app\modules\sale\models;

use Yii;

/**
 * This is the model class for table "{{%room_type}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property RoomDetail[] $roomDetails
 */
class RoomType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%room_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'name', 'createdBy', 'createdAt'], 'required'],
            [['status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
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
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[RoomDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoomDetails()
    {
        return $this->hasMany(RoomDetail::class, ['roomTypeId' => 'id']);
    }
}
