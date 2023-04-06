<?php

namespace app\modules\sale\models\holiday;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%holiday_category}}".
 *
 * @property int $id
 * @property int $agencyId
 * @property string $uid
 * @property string $name
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Holiday[] $holidays
 */
class HolidayCategory extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%holiday_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'status'], 'required'],
            [['status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt', 'agencyId'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 30],
            [['uid'], 'unique'],
            [['name'], 'unique'],
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
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Holidays]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHolidays()
    {
        return $this->hasMany(Holiday::className(), ['holidayCategoryId' => 'id']);
    }
}
