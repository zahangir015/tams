<?php

namespace app\modules\sale\models;

use app\components\GlobalConstant;
use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

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
 * @property float $reissueCharge
 * @property float $categories
 * @property int $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 */
class Supplier extends ActiveRecord
{
    use BehaviorTrait;

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
            [['name', 'email', 'company', 'type', 'createdBy'], 'required'],
            [['type', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['refundCharge', 'reissueCharge'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 30],
            [['email'], 'string', 'max' => 100],
            [['company'], 'string', 'max' => 150],
            [['categories'], 'safe'],
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
            'reissueCharge' => Yii::t('app', 'Reissue Charge'),
            'categories' => Yii::t('app', 'Categories'),
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
            ->select(['id', 'name', 'company', 'email'])
            ->where(['like', 'name', $query])
            ->orWhere(['like', 'company', $query])
            ->orWhere(['like', 'email', $query])
            ->andWhere(['status' => GlobalConstant::ACTIVE_STATUS])
            ->all();
    }
}
