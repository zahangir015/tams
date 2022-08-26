<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%refund_transaction_details}}`.
 */
class m220826_054401_create_refund_transaction_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%refund_transaction_detail}}', [
            'id' => $this->primaryKey(),
            'refundTransactionID' => $this->integer()->notNull(),
            'refId' => $this->integer()->notNull(),
            'refModel' => $this->string(150)->notNull(),
            'payableAmount' => $this->double()->defaultValue(0),
            'receivableAmount' => $this->double()->defaultValue(0),
            'totalAmount' => $this->double()->defaultValue(0),
            'status' => $this->boolean()->notNull()->defaultValue(1),
        ]);

        // creates index for column `refundTransactionID`
        $this->createIndex(
            'idx-refund-transaction-detail-refundTransactionID',
            'refund_transaction_detail',
            'refundTransactionID'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // creates index for column `refundTransactionID`
        $this->dropIndex(
            'idx-refund-transaction-detail-refundTransactionID',
            'refund_transaction_detail'
        );

        $this->dropTable('{{%refund_transaction_detail}}');
    }
}
