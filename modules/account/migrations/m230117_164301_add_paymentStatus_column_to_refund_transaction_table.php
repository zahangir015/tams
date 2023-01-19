<?php

use app\modules\account\models\RefundTransaction;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%refund_transaction}}`.
 */
class m230117_164301_add_paymentStatus_column_to_refund_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(RefundTransaction::tableName(), 'paymentStatus', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn(RefundTransaction::tableName(), 'PaymentType', "ENUM('Payable', 'Receivable')");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(RefundTransaction::tableName(), 'PaymentType');
    }
}
