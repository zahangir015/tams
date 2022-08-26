<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%refund_transaction}}`.
 */
class m220826_054346_create_refund_transaction_table extends Migration
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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%refund_transaction}}');
    }
}
