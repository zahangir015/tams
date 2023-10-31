<?php

namespace app\modules\account\models;

use app\traits\BehaviorTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "advance_payment".
 *
 * @property int $id
 * @property string $uid
 * @property int $refId
 * @property string $refModel
 * @property string $identificationNumber
 * @property int $bankId
 * @property string $date
 * @property float $paidAmount
 * @property float $processedAmount
 * @property string|null $remarks
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 */
class AdvancePayment extends ActiveRecord
{
    use BehaviorTrait;
    public $name;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'advance_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['refId', 'refModel', 'bankId', 'date', 'identificationNumber'], 'required'],
            [['refId', 'bankId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['date'], 'safe'],
            [['paidAmount', 'processedAmount'], 'number'],
            [['remarks'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['refModel'], 'string', 'max' => 150],
            [['uid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'refId' => 'Ref ID',
            'refModel' => 'Ref Model',
            'bankId' => 'Bank ID',
            'date' => 'Date',
            'paidAmount' => 'Paid Amount',
            'remarks' => 'Remarks',
            'status' => 'Status',
            'createdBy' => 'Created By',
            'createdAt' => 'Created At',
            'updatedBy' => 'Updated By',
            'updatedAt' => 'Updated At',
        ];
    }
}
