<?php

namespace app\modules\sale\models;

use app\components\GlobalConstant;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%provider}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $agencyId
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
    use BehaviorTrait;

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
    public function rules(): array
    {
        return [
            [['code', 'name', 'createdBy'], 'required'],
            [['status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['code'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 150],
            [['uid'], 'unique'],
            [['code', 'agencyId'], 'unique', 'targetAttribute' => ['code', 'agencyId']],
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
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    public static function query(): array
    {
        // try retrieving $data from cache
        $cache = Yii::$app->cache;
        $key = 'provider'.Yii::$app->user->identity->agencyId;
        $data = $cache->get($key);

        if ($data === false) {
            // $data is not found in cache, calculate it from scratch
            $providers = self::find()
                ->select(['id', 'name', 'code'])
                ->where([self::tableName() . '.status' => GlobalConstant::ACTIVE_STATUS])
                ->andWhere([self::tableName() . '.agencyId' => Yii::$app->user->identity->agencyId])
                ->all();

            $data = ArrayHelper::map($providers, 'id', function ($provider) {
                return $provider->name . ' (' . $provider->code.')';
            });

            // store $data in cache so that it can be retrieved next time
            $cache->set($key, $data);
        }

        return $data;
    }
}
