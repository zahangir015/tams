<?php

namespace app\modules\sale\models;

use TimestampTrait;
use Yii;

/**
 * This is the model class for table "{{%supplier}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $email
 * @property string $company
 * @property string|null $address
 * @property string|null $phone
 * @property int $type
 * @property float $refundCharge
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 */
class Supplier extends \yii\db\ActiveRecord
{
    use TimestampTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%supplier}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uid', 'name', 'email', 'company', 'type', 'createdBy', 'createdAt'], 'required'],
            [['type', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['refundCharge'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 30],
            [['email'], 'string', 'max' => 100],
            [['company'], 'string', 'max' => 150],
            [['address', 'phone'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['name'], 'unique'],
            [['email'], 'unique'],
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
            'email' => Yii::t('app', 'Email'),
            'company' => Yii::t('app', 'Company'),
            'address' => Yii::t('app', 'Address'),
            'phone' => Yii::t('app', 'Phone'),
            'type' => Yii::t('app', 'Type'),
            'refundCharge' => Yii::t('app', 'Refund Charge'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }
}
