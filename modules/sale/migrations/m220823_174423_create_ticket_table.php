<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket}}`.
 */
class m220823_174423_create_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'motherTicketId' => $this->integer()->null(),
            'airlineId' => $this->integer()->notNull(),
            'providerId' => $this->integer()->notNull(),
            'invoiceId' => $this->integer()->null(),
            'customerId' => $this->integer()->notNull(),
            'customerCategory' => $this->string('10')->notNull(),
            'paxName' => $this->string(120)->notNull(),
            'paxType' => $this->string(1)->null(),
            'eTicket' => $this->string(50)->notNull(),
            'pnrCode' => $this->string(50)->notNull(),
            'type' => "ENUM('New','Reissue','Refund','EMD Voucher','Refund Requested', 'Deportee') NOT NULL" ,
            'tripType' => "ENUM('One Way','Return') DEFAULT NULL",
            'bookedOnline' => $this->boolean()->defaultValue(0),
            'flightType' => $this->boolean()->defaultValue(0),
            'seatClass' => $this->string()->notNull(),
            'codeShare' => $this->boolean()->defaultValue(0),
            'reference' => $this->string()->null(),
            'issueDate' => $this->date()->notNull(),
            'departureDate' => $this->date()->null(),
            'refundRequestDate' => $this->date()->null(),
            'route' => $this->string()->null(),
            'numberOfSegment' => $this->integer()->defaultValue(0),
            'baseFare' => $this->double()->defaultValue(0),
            'tax' => $this->double()->defaultValue(0),
            'otherTax' => $this->double()->defaultValue(0),
            'commission' => $this->double()->defaultValue(0),
            'commissionReceived' => $this->double()->defaultValue(0),
            'incentive' => $this->double()->defaultValue(0),
            'incentiveReceived' => $this->double()->defaultValue(0),
            'govTax' => $this->double()->defaultValue(0),
            'serviceCharge' => $this->double()->defaultValue(0),
            'ait' => $this->double()->defaultValue(0),
            'quoteAmount' => $this->double()->defaultValue(0),
            'receivedAmount' => $this->double()->defaultValue(0),
            'paymentStatus' => "ENUM('Full Paid','Partially Paid','Due','Refund Adjustment') DEFAULT 'Due'",
            'costOfSale' => $this->double()->defaultValue(0),
            'netProfit' => $this->double()->defaultValue(0),
            'baggage' => $this->string()->null(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        // creates index for column `motherTicketId`
        $this->createIndex(
            'idx-ticket-motherTicketId',
            'ticket',
            'motherTicketId'
        );

        // creates index for column `airlineId`
        $this->createIndex(
            'idx-ticket-airlineId',
            'ticket',
            'airlineId'
        );

        // add foreign key for table `airline`
        $this->addForeignKey(
            'fk-ticket-airlineId',
            'ticket',
            'airlineId',
            'airline',
            'id',
            'CASCADE'
        );

        // creates index for column `providerId`
        $this->createIndex(
            'idx-ticket-providerId',
            'ticket',
            'providerId'
        );

        // add foreign key for table `provider`
        $this->addForeignKey(
            'fk-ticket-providerId',
            'ticket',
            'providerId',
            'provider',
            'id',
            'CASCADE'
        );

        // creates index for column `invoiceId`
        $this->createIndex(
            'idx-ticket-invoiceId',
            'ticket',
            'invoiceId'
        );

        // add foreign key for table `invoice`
        $this->addForeignKey(
            'fk-ticket-invoiceId',
            'ticket',
            'invoiceId',
            'invoice',
            'id',
            'CASCADE'
        );

        // creates index for column `customerId`
        $this->createIndex(
            'idx-ticket-customerId',
            'ticket',
            'customerId'
        );

        // add foreign key for table `customer`
        $this->addForeignKey(
            'fk-ticket-customerId',
            'ticket',
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
        // drops index for column `motherTicketId`
        $this->dropIndex(
            'idx-ticket_motherTicketId',
            'ticket'
        );

        // drops foreign key for table `airline`
        $this->dropForeignKey(
            'fk-ticket-airlineId',
            'ticket'
        );

        // drops index for column `airlineId`
        $this->dropIndex(
            'idx-ticket-airlineId',
            'ticket'
        );

        // drops foreign key for table `provider`
        $this->dropForeignKey(
            'fk-ticket-providerId',
            'ticket'
        );

        // drops index for column `providerId`
        $this->dropIndex(
            'idx-ticket-providerId',
            'ticket'
        );

        // drops foreign key for table `invoice`
        $this->dropForeignKey(
            'fk-ticket-invoiceId',
            'ticket'
        );

        // drops index for column `invoiceId`
        $this->dropIndex(
            'idx-ticket-invoiceId',
            'ticket'
        );

        // drops foreign key for table `customer`
        $this->dropForeignKey(
            'fk-ticket-customerId',
            'ticket'
        );

        // drops index for column `customerId`
        $this->dropIndex(
            'idx-ticket-customerId',
            'ticket'
        );


        $this->dropTable('{{%ticket}}');
    }
}
