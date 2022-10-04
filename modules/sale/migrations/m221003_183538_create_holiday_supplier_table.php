<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%holiday_supplier}}`.
 */
class m221003_183538_create_holiday_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%holiday_supplier}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'holidayId' => $this->integer(11)->notNull(),
            'billId' => $this->integer(11)->null(),
            'supplierId' => $this->integer(11)->notNull(),
            'supplierRef' => $this->string()->null(),
            'issueDate' => $this->date()->notNull(),
            'departureDate' => $this->date()->notNull(),
            'refundRequestDate' => $this->date()->null(),
            'type' => "ENUM('New', 'Refund', 'Refund Requested') NOT NULL" ,
            'serviceDetails' => $this->string()->null(),
            'quantity' => $this->integer()->notNull(),
            'unitPrice' => $this->integer()->notNull(),
            'costOfSale' => $this->double()->notNull(),
            'paidAmount' => $this->double()->defaultValue(0),
            'paymentStatus' => "ENUM('Full Paid','Partially Paid','Due','Refund Adjustment') DEFAULT 'Due'",
            'status' => $this->boolean()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%holiday_supplier}}');
    }
}
