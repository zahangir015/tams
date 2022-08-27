<?php

namespace app\modules\account\models;

use Yii;

/**
 * This is the model class for table "{{%invoice_detail}}".
 *
 * @property int $id
 * @property int $invoiceId
 * @property int $refId
 * @property string $refModel
 * @property float|null $paidAmount
 * @property float|null $dueAmount
 * @property int|null $status
 */
class InvoiceDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%invoice_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoiceId', 'refId', 'refModel'], 'required'],
            [['invoiceId', 'refId', 'status'], 'integer'],
            [['paidAmount', 'dueAmount'], 'number'],
            [['refModel'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'invoiceId' => Yii::t('app', 'Invoice ID'),
            'refId' => Yii::t('app', 'Ref ID'),
            'refModel' => Yii::t('app', 'Ref Model'),
            'paidAmount' => Yii::t('app', 'Paid Amount'),
            'dueAmount' => Yii::t('app', 'Due Amount'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
