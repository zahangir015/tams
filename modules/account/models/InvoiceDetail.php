<?php

namespace app\modules\account\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
class  InvoiceDetail extends ActiveRecord
{
    use BehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%invoice_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
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
    public function attributeLabels(): array
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

    public function getService(): ActiveQuery
    {
        return $this->hasOne($this->refModel::className(), ['id' => 'refId']);
    }

    public function getInvoice(): ActiveQuery
    {
        return $this->hasOne(Invoice::class, ['id' => 'invoiceId']);
    }


    public function getIdentificationNumber($service): string
    {
        return !empty($service->identificationNumber) ? $service->identificationNumber : $service->eTicket;
    }
}
