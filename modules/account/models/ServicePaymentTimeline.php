<?php

namespace app\modules\account\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%service_payment_timeline}}".
 *
 * @property int $id
 * @property string $uid
 * @property string $date
 * @property int $refId
 * @property string $refModel
 * @property int|null $subRefId
 * @property string|null $subRefModel
 * @property float|null $paidAmount
 * @property float|null $dueAmount
 * @property int|null $status
 */
class ServicePaymentTimeline extends ActiveRecord
{
    use BehaviorTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%service_payment_timeline}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['date', 'refId', 'refModel'], 'required'],
            [['date'], 'safe'],
            [['refId', 'subRefId', 'status'], 'integer'],
            [['paidAmount', 'dueAmount'], 'number'],
            [['uid'], 'string', 'max' => 36],
            [['refModel', 'subRefModel'], 'string', 'max' => 150],
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
            'date' => Yii::t('app', 'Date'),
            'refId' => Yii::t('app', 'Ref ID'),
            'refModel' => Yii::t('app', 'Ref Model'),
            'subRefId' => Yii::t('app', 'Sub Ref ID'),
            'subRefModel' => Yii::t('app', 'Sub Ref Model'),
            'paidAmount' => Yii::t('app', 'Paid Amount'),
            'dueAmount' => Yii::t('app', 'Due Amount'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
