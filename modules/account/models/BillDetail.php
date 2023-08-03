<?php

namespace app\modules\account\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%bill_detail}}".
 *
 * @property int $id
 * @property int $billId
 * @property int $refId
 * @property string $refModel
 * @property float|null $paidAmount
 * @property float|null $dueAmount
 * @property int|null $status
 */
class BillDetail extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%bill_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['billId', 'refId', 'refModel'], 'required'],
            [['billId', 'refId', 'status'], 'integer'],
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
            'billId' => Yii::t('app', 'Bill ID'),
            'refId' => Yii::t('app', 'Ref ID'),
            'refModel' => Yii::t('app', 'Ref Model'),
            'paidAmount' => Yii::t('app', 'Paid Amount'),
            'dueAmount' => Yii::t('app', 'Due Amount'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
