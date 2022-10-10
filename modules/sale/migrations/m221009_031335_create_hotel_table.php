<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hotel}}`.
 */
class m221009_031335_create_hotel_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hotel}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'motherId' => $this->integer()->null(),
            'invoiceId' => $this->integer()->null(),
            'identificationNumber' => $this->string(32)->notNull()->unique(),
            'customerId' => $this->integer()->notNull(),
            'customerCategory' => $this->string('10')->notNull(),
            'voucherNumber' => $this->string()->null(),
            'reservationCode' => $this->string()->null(),
            'type' => "ENUM('New', 'Refund', 'Refund Requested') NOT NULL DEFAULT 'New'" ,
            'issueDate' => $this->date()->notNull(),
            'refundRequestDate' => $this->date()->null(),
            'checkInDate' => $this->date()->notNull(),
            'checkOutDate' => $this->date()->notNull(),
            'freeCancellationDate' => $this->date()->null(),
            'totalNights' => $this->integer()->notNull(),
            'route' => $this->string()->notNull(),
            'isRefundable' => $this->boolean()->defaultValue(1),
            'quoteAmount' => $this->double()->notNull(),
            'costOfSale' => $this->double()->notNull(),
            'netProfit' => $this->double()->notNull(),
            'receivedAmount' => $this->double()->notNull()->defaultValue(0),
            'paymentStatus' => "ENUM('Full Paid','Partially Paid','Due','Refund Adjustment') DEFAULT 'Due'",
            'isOnlineBooked' => $this->boolean()->defaultValue(0),
            'reference' => $this->string()->null(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        // creates index for column `identificationNumber`
        $this->createIndex(
            'idx-hotel-identificationNumber',
            'hotel',
            'identificationNumber'
        );

        // creates index for column `voucherNumber`
        $this->createIndex(
            'idx-hotel-voucherNumber',
            'hotel',
            'voucherNumber'
        );

        // creates index for column `reservationCode`
        $this->createIndex(
            'idx-hotel-reservationCode',
            'hotel',
            'reservationCode'
        );

        // creates index for column `invoiceId`
        $this->createIndex(
            'idx-hotel-invoiceId',
            'hotel',
            'invoiceId'
        );

        // add foreign key for table `invoice`
        $this->addForeignKey(
            'fk-hotel-invoiceId',
            'hotel',
            'invoiceId',
            'invoice',
            'id',
            'CASCADE'
        );

        // creates index for column `customerId`
        $this->createIndex(
            'idx-hotel-customerId',
            'hotel',
            'customerId'
        );

        // add foreign key for table `customer`
        $this->addForeignKey(
            'fk-hotel-customerId',
            'hotel',
            'customerId',
            'customer',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `identificationNumber`
        $this->dropIndex(
            'idx-hotel-identificationNumber',
            'hotel'
        );

        // drops index for column `voucherNumber`
        $this->dropIndex(
            'idx-hotel-voucherNumber',
            'hotel'
        );

        // drops index for column `reservationCode`
        $this->dropIndex(
            'idx-hotel-reservationCode',
            'hotel'
        );

        // drops foreign key for table `invoice`
        $this->dropForeignKey(
            'fk-hotel-invoiceId',
            'hotel'
        );

        // drops index for column `invoiceId`
        $this->dropIndex(
            'idx-hotel-invoiceId',
            'hotel'
        );

        // drops foreign key for table `customer`
        $this->dropForeignKey(
            'fk-hotel-customerId',
            'hotel'
        );

        // drops index for column `customerId`
        $this->dropIndex(
            'idx-hotel-customerId',
            'hotel'
        );

        $this->dropTable('{{%hotel}}');
    }
}
