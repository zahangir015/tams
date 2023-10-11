<?php

namespace app\modules\account\models;

use Yii;

/**
 * This is the model class for table "{{%contra_entry}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $identificationNumber
 * @property int $bankFrom
 * @property int $bankTo
 * @property float|null $amount
 * @property string $paymentDate
 * @property string|null $remarks
 * @property int $status
 * @property int $createdAt
 * @property int|null $updatedAt
 * @property int $createdBy
 * @property int|null $updatedBy
 */
class ContraEntry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contra_entry}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'identificationNumber', 'bankFrom', 'bankTo', 'paymentDate', 'createdAt', 'createdBy'], 'required'],
            [['bankFrom', 'bankTo', 'status', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy'], 'integer'],
            [['amount'], 'number'],
            [['paymentDate'], 'safe'],
            [['uid'], 'string', 'max' => 36],
            [['identificationNumber', 'remarks'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['identificationNumber'], 'unique'],
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
            'identificationNumber' => Yii::t('app', 'Identification Number'),
            'bankFrom' => Yii::t('app', 'Bank From'),
            'bankTo' => Yii::t('app', 'Bank To'),
            'amount' => Yii::t('app', 'Amount'),
            'paymentDate' => Yii::t('app', 'Payment Date'),
            'remarks' => Yii::t('app', 'Remarks'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedBy' => Yii::t('app', 'Updated By'),
        ];
    }
}
