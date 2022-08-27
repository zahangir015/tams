<?php

namespace app\modules\account\models;

use app\traits\BehaviorTrait;
use Yii;

/**
 * This is the model class for table "{{%bank_account}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $shortName
 * @property string $accountName
 * @property string $accountNumber
 * @property string $branch
 * @property string|null $routingNumber
 * @property string|null $swiftCode
 * @property string|null $code
 * @property float|null $paymentCharge
 * @property string|null $logo
 * @property string|null $tag
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Transaction[] $transactions
 */
class BankAccount extends \yii\db\ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bank_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'shortName', 'accountName', 'accountNumber', 'branch', 'createdBy'], 'required'],
            [['paymentCharge'], 'number'],
            [['tag'], 'safe'],
            [['status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['uid'], 'string', 'max' => 36],
            [['name', 'accountName'], 'string', 'max' => 150],
            [['shortName'], 'string', 'max' => 20],
            [['accountNumber'], 'string', 'max' => 60],
            [['branch', 'routingNumber', 'swiftCode', 'code'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
            [['uid'], 'unique'],
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
            'name' => Yii::t('app', 'Name'),
            'shortName' => Yii::t('app', 'Short Name'),
            'accountName' => Yii::t('app', 'Account Name'),
            'accountNumber' => Yii::t('app', 'Account Number'),
            'branch' => Yii::t('app', 'Branch'),
            'routingNumber' => Yii::t('app', 'Routing Number'),
            'swiftCode' => Yii::t('app', 'Swift Code'),
            'code' => Yii::t('app', 'Code'),
            'paymentCharge' => Yii::t('app', 'Payment Charge'),
            'logo' => Yii::t('app', 'Logo'),
            'tag' => Yii::t('app', 'Tag'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['bankId' => 'id']);
    }
}
