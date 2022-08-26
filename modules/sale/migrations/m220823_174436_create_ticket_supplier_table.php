<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket_supplier}}`.
 */
class m220823_174436_create_ticket_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket_supplier}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'ticketId' => $this->integer(11)->notNull(),
            'supplierId' => $this->integer(11)->notNull(),
            'airlineId' => $this->integer(11)->notNull(),
            'billId' => $this->integer(11)->null(),
            'issueDate' => $this->integer(11)->notNull(),
            'refundRequestDate' => $this->date()->null(),
            'eTicket' => $this->string('50')->notNull(),
            'pnrCode' => $this->string('50')->notNull(),
            'type' => "ENUM('New','Reissue','Refund','EMD Voucher','Refund Requested', 'Deportee') NOT NULL" ,
            'baseFare' => $this->double()->defaultValue(0),
            'tax' => $this->double()->defaultValue(0),
            'otherTax' => $this->double()->defaultValue(0),
            'costOfSale' => $this->double()->notNull(),
            'paidAmount' => $this->double()->defaultValue(0),
            'paymentStatus' => "ENUM('Full Paid','Partially Paid','Due','Refund Adjustment') DEFAULT 'Due'",
            'status' => $this->boolean()->notNull()->defaultValue(1),
        ]);

        // creates index for column `ticketId`
        $this->createIndex(
            'idx-ticket-supplier-ticketId',
            'ticket_supplier',
            'ticketId'
        );

        // add foreign key for table `ticket`
        $this->addForeignKey(
            'fk-ticket-supplier-ticketId',
            'ticket_supplier',
            'ticketId',
            'ticket',
            'id',
            'CASCADE'
        );

        // creates index for column `supplierId`
        $this->createIndex(
            'idx-ticket-supplier-supplierId',
            'ticket_supplier',
            'ticketId'
        );

        // add foreign key for table `supplier`
        $this->addForeignKey(
            'fk-ticket-supplier-supplierId',
            'ticket_supplier',
            'supplierId',
            'supplier',
            'id',
            'CASCADE'
        );

        // creates index for column `airlineId`
        $this->createIndex(
            'idx-ticket-supplier-airlineId',
            'ticket_supplier',
            'airlineId'
        );

        // add foreign key for table `airline`
        $this->addForeignKey(
            'fk-ticket-supplier-airlineId',
            'ticket_supplier',
            'airlineId',
            'airline',
            'id',
            'CASCADE'
        );

        // creates index for column `billId`
        $this->createIndex(
            'idx-ticket-supplier-billId',
            'ticket_supplier',
            'airlineId'
        );

        // add foreign key for table `bill`
        $this->addForeignKey(
            'fk-ticket-supplier-billId',
            'ticket_supplier',
            'billId',
            'bill',
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
            'fk-ticket-supplier-ticketId',
            'ticket_supplier'
        );

        // drops index for column `ticketId`
        $this->dropIndex(
            'idx-ticket-supplier-ticketId',
            'ticket_supplier'
        );

        // drops foreign key for table `supplier`
        $this->dropForeignKey(
            'fk-ticket-supplier-supplierId',
            'ticket_supplier'
        );

        // drops index for column `supplierId`
        $this->dropIndex(
            'idx-ticket-supplier-supplierId',
            'ticket_supplier'
        );

        // drops foreign key for table `airline`
        $this->dropForeignKey(
            'fk-ticket-supplier-airlineId',
            'ticket_supplier'
        );

        // drops index for column `airlineId`
        $this->dropIndex(
            'idx-ticket-supplier-airlineId',
            'ticket_supplier'
        );

        // drops foreign key for table `bill`
        $this->dropForeignKey(
            'fk-ticket-supplier-billId',
            'ticket_supplier'
        );

        // drops index for column `billId`
        $this->dropIndex(
            'idx-ticket-supplier-billId',
            'ticket_supplier'
        );

        $this->dropTable('{{%ticket_supplier}}');
    }
}
