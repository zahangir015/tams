<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%refund_transaction}}`.
 */
class m220823165647_create_refund_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%refund_transaction}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'refId' => $this->integer()->notNull(),
            'refModel' => $this->string(150)->notNull(),
            'payableAmount' => $this->double()->defaultValue(0),
            'receivableAmount' => $this->double()->defaultValue(0),
            'totalAmount' => $this->double()->defaultValue(0),
            'paymentStatus' => "ENUM('Payable', 'Receivable')",
            'adjustedAmount' => $this->double()->defaultValue(0),
            'isAdjusted' => $this->boolean()->defaultValue(0),
            'remarks' => $this->text(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        // creates index for column `refId`
        $this->createIndex(
            'idx-refund-transaction-refId',
            'refund_transaction',
            'refId'
        );

        // creates index for column `refModel`
        $this->createIndex(
            'idx-refund-transaction-refModel',
            'refund_transaction',
            'refModel'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `refId`
        $this->dropIndex(
            'idx-refund-transaction-refId',
            'refund_transaction'
        );

        // drops index for column `refModel`
        $this->dropIndex(
            'idx-refund-transaction-refModel',
            'refund_transaction'
        );

        $this->dropTable('{{%refund_transaction}}');
    }
}
