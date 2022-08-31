<?php

namespace app\modules\sale\models;

use app\components\GlobalConstant;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%customer}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $company
 * @property string $customerCode
 * @property string $category
 * @property string $email
 * @property string|null $address
 * @property string|null $phone
 * @property int|null $creditModality
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 */
class Customer extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%customer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'company', 'customerCode', 'category', 'email', 'createdBy'], 'required'],
            [['category'], 'string'],
            [['creditModality', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name', 'company', 'email', 'address'], 'string', 'max' => 255],
            [['customerCode'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 50],
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
            'company' => Yii::t('app', 'Company'),
            'customerCode' => Yii::t('app', 'Customer Code'),
            'category' => Yii::t('app', 'Category'),
            'email' => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Address'),
            'phone' => Yii::t('app', 'Phone'),
            'creditModality' => Yii::t('app', 'Credit Modality'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    public static function query($query): array
    {
        return self::find()
            ->select(['id', 'name', 'company', 'email', 'customerCode'])
            ->where(['like', 'name', $query])
            ->orWhere(['like', 'company', $query])
            ->orWhere(['like', 'customerCode', $query])
            ->orWhere(['like', 'email', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}
