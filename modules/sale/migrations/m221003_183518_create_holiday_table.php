<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%holiday}}`.
 */
class m221003_183518_create_holiday_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%holiday}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'motherId' => $this->integer()->null(),
            'invoiceId' => $this->integer()->null(),
            'holidayCategoryId' => $this->integer()->notNull(),
            'identificationNumber' => $this->string(32)->notNull(),
            'customerId' => $this->integer()->notNull(),
            'customerCategory' => $this->string('10')->notNull(),
            'type' => "ENUM('New', 'Refund', 'Refund Requested') NOT NULL" ,
            'issueDate' => $this->date()->notNull(),
            'departureDate' => $this->date()->null(),
            'refundRequestDate' => $this->date()->null(),
            'quoteAmount' => $this->double()->notNull(),
            'costOfSale' => $this->double()->notNull(),
            'netProfit' => $this->double()->notNull(),
            'receivedAmount' => $this->double()->notNull()->defaultValue(0),
            'paymentStatus' => "ENUM('Full Paid','Partially Paid','Due','Refund Adjustment') DEFAULT 'Due'",
            'isOnlineBooked' => $this->boolean()->defaultValue(0),
            'route' => $this->string()->notNull(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        // creates index for column `holidayCategoryId`
        $this->createIndex(
            'idx-holiday-holidayCategoryId',
            'holiday',
            'holidayCategoryId'
        );

        // add foreign key for table `holiday_category`
        $this->addForeignKey(
            'fk-holiday-holidayCategoryId',
            'holiday',
            'holidayCategoryId',
            'holiday_category',
            'id',
            'CASCADE'
        );

        // creates index for column `invoiceId`
        $this->createIndex(
            'idx-holiday-invoiceId',
            'holiday',
            'invoiceId'
        );

        // add foreign key for table `invoice`
        $this->addForeignKey(
            'fk-holiday-invoiceId',
            'holiday',
            'invoiceId',
            'invoice',
            'id',
            'CASCADE'
        );

        // creates index for column `customerId`
        $this->createIndex(
            'idx-holiday-customerId',
            'holiday',
            'customerId'
        );

        // add foreign key for table `customer`
        $this->addForeignKey(
            'fk-holiday-customerId',
            'holiday',
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
        // drops foreign key for table `holiday_category`
        $this->dropForeignKey(
            'fk-holiday-holidayCategoryId',
            'holiday'
        );

        // drops index for column `holidayCategoryId`
        $this->dropIndex(
            'idx-holiday-holidayCategoryId',
            'holiday'
        );

        // drops foreign key for table `invoice`
        $this->dropForeignKey(
            'fk-holiday-invoiceId',
            'holiday'
        );

        // drops index for column `invoiceId`
        $this->dropIndex(
            'idx-holiday-invoiceId',
            'holiday'
        );

        // drops foreign key for table `customer`
        $this->dropForeignKey(
            'fk-holiday-customerId',
            'holiday'
        );

        // drops index for column `customerId`
        $this->dropIndex(
            'idx-holiday-customerId',
            'holiday'
        );

        $this->dropTable('{{%holiday}}');
    }
}
