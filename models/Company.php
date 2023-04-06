<?php

namespace app\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%company}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
 * @property string $name
 * @property string $shortName
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property string $logo
 */
class Company extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%company}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'shortName', 'phone', 'email', 'address', 'logo'], 'required'],
            [['agencyId'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 120],
            [['shortName'], 'string', 'max' => 10],
            [['phone', 'email'], 'string', 'max' => 100],
            [['address', 'logo'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['name'], 'unique'],
            [['shortName'], 'unique'],
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
            'name' => Yii::t('app', 'Name'),
            'shortName' => Yii::t('app', 'Short Name'),
            'phone' => Yii::t('app', 'Phone'),
            'email' => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Address'),
            'logo' => Yii::t('app', 'Logo'),
        ];
    }
}
