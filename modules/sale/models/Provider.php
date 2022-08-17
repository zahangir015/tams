<?php

namespace app\modules\sale\models;

use TimestampTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%provider}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $code
 * @property string $name
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 */
class Provider extends ActiveRecord
{
    use TimestampTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%provider}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'code', 'name', 'createdBy', 'createdAt'], 'required'],
            [['status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['code'], 'unique'],
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
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }
}
