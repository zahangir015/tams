<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%visa}}`.
 */
class m221009_043809_create_visa_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%visa}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'motherId' => $this->integer()->null(),
            'invoiceId' => $this->integer()->null(),
            'identificationNumber' => $this->string(32)->notNull()->unique(),
            'customerId' => $this->integer()->notNull(),
            'customerCategory' => $this->string('10')->notNull(),
            'type' => "ENUM('New', 'Refund', 'Refund Requested') NOT NULL DEFAULT 'New'" ,
            'issueDate' => $this->date()->notNull(),
            'refundRequestDate' => $this->date()->null(),
            'totalQuantity' => $this->integer()->notNull(),
            'processStatus' => $this->boolean()->defaultValue(1),
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
            'idx-visa-identificationNumber',
            'visa',
            'identificationNumber'
        );

        // creates index for column `invoiceId`
        $this->createIndex(
            'idx-visa-invoiceId',
            'visa',
            'invoiceId'
        );

        // add foreign key for table `invoice`
        $this->addForeignKey(
            'fk-visa-invoiceId',
            'visa',
            'invoiceId',
            'invoice',
            'id',
            'CASCADE'
        );

        // creates index for column `customerId`
        $this->createIndex(
            'idx-visa-customerId',
            'visa',
            'customerId'
        );

        // add foreign key for table `customer`
        $this->addForeignKey(
            'fk-visa-customerId',
            'visa',
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
            'idx-visa-identificationNumber',
            'visa'
        );

        // drops foreign key for table `invoice`
        $this->dropForeignKey(
            'fk-visa-invoiceId',
            'visa'
        );

        // drops index for column `invoiceId`
        $this->dropIndex(
            'idx-visa-invoiceId',
            'visa'
        );

        // drops foreign key for table `customer`
        $this->dropForeignKey(
            'fk-visa-customerId',
            'visa'
        );

        // drops index for column `customerId`
        $this->dropIndex(
            'idx-visa-customerId',
            'visa'
        );

        $this->dropTable('{{%visa}}');
    }
}
