<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket_refund}}`.
 */
class m220823_174938_create_ticket_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket_refund}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'ticketId' => $this->integer()->notNull(),
            'refId' => $this->integer()->notNull(),
            'refundTransactionId' => $this->integer()->null(),
            'refModel' => $this->string(150)->notNull(),
            'refundRequestDate' => $this->date()->notNull(),
            'refundStatus' => "ENUM('NO SHOW','NOT NO SHOW','TAX REFUND','HALF PORTION REFUND','FULL REFUND','VOID','HALF PORTION TAX REFUND') DEFAULT NULL",
            'refundMedium' => "ENUM('GDS','BSP','Supplier') DEFAULT NULL",
            'refundMethod' => "ENUM('Credit/Debit Card','Bank Account','Refund Adjustment') DEFAULT NULL",
            'supplierRefundCharge' => $this->double()->defaultValue(0),
            'airlineRefundCharge' => $this->double()->defaultValue(0),
            'serviceCharge' => $this->double()->defaultValue(0),
            'isRefunded' => $this->boolean()->defaultValue(0),
            'refundDate' => $this->date()->null(),
            'refundedAmount' => $this->double()->null(),
            'remarks' => $this->text(),
            'status' => $this->boolean()->defaultValue(1),
        ]);

        // creates index for column `ticketId`
        $this->createIndex(
            'idx-ticket-refund-ticketId',
            'ticket_refund',
            'ticketId'
        );

        // add foreign key for table `ticket`
        $this->addForeignKey(
            'fk-ticket-refund-ticketId',
            'ticket_refund',
            'ticketId',
            'ticket',
            'id',
            'CASCADE'
        );

        // creates index for column `refundTransactionId`
        $this->createIndex(
            'idx-ticket-refund-refundTransactionId',
            'ticket_refund',
            'refundTransactionId'
        );

        // add foreign key for table `refundTransaction`
        $this->addForeignKey(
            'fk-ticket-refund-refundTransactionId',
            'ticket_refund',
            'refundTransactionId',
            'refund_transaction',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `ticket`
        $this->dropForeignKey(
            'fk-ticket-refund-ticketId',
            'ticket_refund'
        );

        // drops index for column `ticketId`
        $this->dropIndex(
            'idx-ticket-refund-ticketId',
            'ticket_refund'
        );

        // drops foreign key for table `refundTransaction`
        $this->dropForeignKey(
            'fk-ticket-refund-refundTransactionId',
            'ticket_refund'
        );

        // drops index for column `refundTransactionId`
        $this->dropIndex(
            'idx-ticket-refund-refundTransactionId',
            'ticket_refund'
        );

        $this->dropTable('{{%ticket_refund}}');
    }
}
