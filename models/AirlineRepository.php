<?php

namespace app\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "airline_repository".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $iataCode
 * @property int|null $status
 */
class AirlineRepository extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'airline_repository';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'name', 'iataCode'], 'required'],
            [['status'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 120],
            [['iataCode'], 'string', 'max' => 5],
            [['uid'], 'unique'],
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
            'iataCode' => Yii::t('app', 'Iata Code'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
