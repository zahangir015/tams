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
            'motherTicketId' => $this->integer()->null(),
            'airlineId' => $this->integer()->notNull(),
            'providerId' => $this->integer()->notNull(),
            'invoiceId' => $this->integer()->null(),
            'customerId' => $this->integer()->notNull(),
            'customerCategory' => $this->string('10')->notNull(),
            'paxName' => $this->string(120)->notNull(),
            'paxType' => $this->string(10)->null(),
            'eTicket' => $this->string('50')->notNull(),
            'pnrCode' => $this->string('50')->notNull(),
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
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
            'updatedAt' => $this->integer()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ticket}}');
    }
}
