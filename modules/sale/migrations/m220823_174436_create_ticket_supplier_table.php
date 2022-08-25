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
            'ticketId' => $this->integer(11)->notNull(),
            'supplierId' => $this->integer(11)->notNull(),
            'airlineId' => $this->integer(11)->notNull(),
            'billId' => $this->integer(11)->null(),
            'issueDate' => $this->integer(11)->notNull(),
            'eTicket' => $this->string('50')->notNull(),
            'pnrCode' => $this->string('50')->notNull(),
            'type' => "ENUM('New','Reissue','Refund','EMD Voucher','Refund Requested', 'Deportee') NOT NULL" ,
            'costOfSale' => $this->double()->notNull(),
            'paidAmount' => $this->double()->defaultValue(0),
            'paymentStatus' => "ENUM('Full Paid','Partially Paid','Due','Refund Adjustment') DEFAULT 'Due'",
            ''
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ticket_supplier}}');
    }
}
