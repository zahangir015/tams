<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hotel_supplier}}`.
 */
class m221009_031501_create_hotel_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hotel_supplier}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'motherHotelSupplierId' => $this->integer(11)->null(),
            'hotelId' => $this->integer(11)->notNull(),
            'billId' => $this->integer(11)->null(),
            'supplierId' => $this->integer(11)->notNull(),
            'supplierRef' => $this->string()->notNull(),
            'issueDate' => $this->date()->notNull(),
            'refundRequestDate' => $this->date()->null(),
            'type' => "ENUM('New', 'Refund', 'Refund Requested') NOT NULL" ,
            'serviceDetails' => $this->string()->null(),
            'numberOfNights' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'unitPrice' => $this->double()->notNull(),
            'costOfSale' => $this->double()->notNull(),
            'paidAmount' => $this->double()->defaultValue(0),
            'paymentStatus' => "ENUM('Full Paid','Partially Paid','Due','Refund Adjustment') DEFAULT 'Due'",
            'status' => $this->boolean()->defaultValue(1),
        ]);

        // creates index for column `hotelId`
        $this->createIndex(
            'idx-hotel-supplier-hotelId',
            'hotel_supplier',
            'hotelId'
        );

        // add foreign key for table `hotelId`
        $this->addForeignKey(
            'fk-hotel-supplier-hotelId',
            'hotel_supplier',
            'hotelId',
            'hotel',
            'id',
            'CASCADE'
        );

        // creates index for column `supplierId`
        $this->createIndex(
            'idx-hotel-supplier-supplierId',
            'hotel_supplier',
            'supplierId'
        );

        // add foreign key for table `supplier`
        $this->addForeignKey(
            'fk-hotel-supplier-supplierId',
            'hotel_supplier',
            'supplierId',
            'supplier',
            'id',
            'CASCADE'
        );

        // creates index for column `billId`
        $this->createIndex(
            'idx-hotel-supplier-billId',
            'hotel_supplier',
            'billId'
        );

        // add foreign key for table `bill`
        $this->addForeignKey(
            'fk-hotel-supplier-billId',
            'hotel_supplier',
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
        // drops foreign key for table `hotel`
        $this->dropForeignKey(
            'fk-hotel-supplier-hotelId',
            'hotel_supplier'
        );

        // drops index for column `hotelId`
        $this->dropIndex(
            'idx-hotel-supplier-hotelId',
            'hotel_supplier'
        );

        // drops foreign key for table `supplier`
        $this->dropForeignKey(
            'fk-hotel-supplier-supplierId',
            'hotel_supplier'
        );

        // drops index for column `supplierId`
        $this->dropIndex(
            'idx-hotel-supplier-supplierId',
            'hotel_supplier'
        );

        // drops foreign key for table `bill`
        $this->dropForeignKey(
            'fk-hotel-supplier-billId',
            'hotel_supplier'
        );

        // drops index for column `billId`
        $this->dropIndex(
            'idx-hotel-supplier-billId',
            'hotel_supplier'
        );

        $this->dropTable('{{%hotel_supplier}}');
    }
}
