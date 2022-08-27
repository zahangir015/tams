<?php

namespace app\modules\account\models;

use Yii;

/**
 * This is the model class for table "{{%bill}}".
 *
 * @property int $id
 * @property string $uid
 * @property int $supplierId
 * @property string $billNumber
 * @property string $date
 * @property float|null $paidAmount
 * @property float|null $dueAmount
 * @property float|null $discountedAmount
 * @property float|null $refundAdjustmentAmount
 * @property string|null $remarks
 * @property int|null $status
 * @property int $createdBy
 * @property int $createdAt
 * @property int|null $updatedBy
 * @property int|null $updatedAt
 *
 * @property Supplier $supplier
 * @property TicketSupplier[] $ticketSuppliers
 */
class Bill extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bill}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'supplierId', 'billNumber', 'date', 'createdBy', 'createdAt'], 'required'],
            [['supplierId', 'status', 'createdBy', 'createdAt', 'updatedBy', 'updatedAt'], 'integer'],
            [['date'], 'safe'],
            [['paidAmount', 'dueAmount', 'discountedAmount', 'refundAdjustmentAmount'], 'number'],
            [['remarks'], 'string'],
            [['uid'], 'string', 'max' => 36],
            [['billNumber'], 'string', 'max' => 64],
            [['uid'], 'unique'],
            [['billNumber'], 'unique'],
            [['supplierId'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::className(), 'targetAttribute' => ['supplierId' => 'id']],
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
            'supplierId' => Yii::t('app', 'Supplier ID'),
            'billNumber' => Yii::t('app', 'Bill Number'),
            'date' => Yii::t('app', 'Date'),
            'paidAmount' => Yii::t('app', 'Paid Amount'),
            'dueAmount' => Yii::t('app', 'Due Amount'),
            'discountedAmount' => Yii::t('app', 'Discounted Amount'),
            'refundAdjustmentAmount' => Yii::t('app', 'Refund Adjustment Amount'),
            'remarks' => Yii::t('app', 'Remarks'),
            'status' => Yii::t('app', 'Status'),
            'createdBy' => Yii::t('app', 'Created By'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplierId']);
    }

    /**
     * Gets query for [[TicketSuppliers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicketSuppliers()
    {
        return $this->hasMany(TicketSupplier::className(), ['billId' => 'id']);
    }
}
