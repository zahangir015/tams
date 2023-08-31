<?php

namespace app\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string $uid
 * @property int $countryId
 * @property string $name
 * @property int|null $status
 *
 * @property Country $country
 */
class City extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'countryId', 'name'], 'required'],
            [['countryId', 'status'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 120],
            [['uid'], 'unique'],
            [['countryId', 'name'], 'unique', 'targetAttribute' => ['countryId', 'name']],
            [['countryId'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['countryId' => 'id']],
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
            'countryId' => Yii::t('app', 'Country ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'countryId']);
    }
}
