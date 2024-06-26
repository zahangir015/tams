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
            'supplierRef' => $this->string()->notNull(),
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

        // creates index for column `holidayId`
        $this->createIndex(
            'idx-holiday-supplier-holidayId',
            'holiday_supplier',
            'holidayId'
        );

        // add foreign key for table `holiday`
        $this->addForeignKey(
            'fk-holiday-supplier-holidayId',
            'holiday_supplier',
            'holidayId',
            'holiday',
            'id',
            'CASCADE'
        );

        // creates index for column `supplierId`
        $this->createIndex(
            'idx-holiday-supplier-supplierId',
            'holiday_supplier',
            'supplierId'
        );

        // add foreign key for table `supplier`
        $this->addForeignKey(
            'fk-holiday-supplier-supplierId',
            'holiday_supplier',
            'supplierId',
            'supplier',
            'id',
            'CASCADE'
        );

        // creates index for column `billId`
        $this->createIndex(
            'idx-holiday-supplier-billId',
            'holiday_supplier',
            'billId'
        );

        // add foreign key for table `bill`
        $this->addForeignKey(
            'fk-holiday-supplier-billId',
            'holiday_supplier',
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
        // drops foreign key for table `holiday`
        $this->dropForeignKey(
            'fk-holiday-supplier-holidayId',
            'holiday_supplier'
        );

        // drops index for column `holidayId`
        $this->dropIndex(
            'idx-holiday-supplier-holidayId',
            'holiday_supplier'
        );

        // drops foreign key for table `supplier`
        $this->dropForeignKey(
            'fk-holiday-supplier-supplierId',
            'holiday_supplier'
        );

        // drops index for column `supplierId`
        $this->dropIndex(
            'idx-holiday-supplier-supplierId',
            'holiday_supplier'
        );

        // drops foreign key for table `bill`
        $this->dropForeignKey(
            'fk-holiday-supplier-billId',
            'holiday_supplier'
        );

        // drops index for column `billId`
        $this->dropIndex(
            'idx-holiday-supplier-billId',
            'holiday_supplier'
        );

        $this->dropTable('{{%holiday_supplier}}');
    }
}
