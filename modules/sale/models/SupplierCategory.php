<?php

namespace app\modules\sale\models;

use TimestampTrait;
use Yii;

/**
 * This is the model class for table "{{%supplier_category}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 */
class SupplierCategory extends \yii\db\ActiveRecord
{
    use TimestampTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%supplier_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'name', 'createdBy', 'createdAt'], 'required'],
            [['status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
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
}
