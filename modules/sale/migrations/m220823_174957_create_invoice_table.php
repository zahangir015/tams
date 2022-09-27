<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoice}}`.
 */
class m220823_174957_create_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'customerId' => $this->integer(11)->notNull(),
            'invoiceNumber' => $this->string(64)->notNull()->unique(),
            'date' => $this->date()->notNull(),
            'expectedPaymentDate' => $this->date()->null(),
            'paidAmount' => $this->double()->defaultValue(0),
            'dueAmount' => $this->double()->defaultValue(0),
            'discountedAmount' => $this->double()->defaultValue(0),
            'refundAdjustmentAmount' => $this->double()->defaultValue(0),
            'remarks' => $this->text(),
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        // creates index for column `invoiceNumber`
        $this->createIndex(
            'idx-invoice-invoiceNumber',
            'invoice',
            'invoiceNumber'
        );

        // creates index for column `customerId`
        $this->createIndex(
            'idx-invoice-customerId',
            'invoice',
            'customerId'
        );

        // add foreign key for table `customer`
        $this->addForeignKey(
            'fk-invoice-customerId',
            'invoice',
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
        // drops index for column `invoiceNumber`
        $this->dropIndex(
            'idx-invoice-invoiceNumber',
            'invoice'
        );

        // drops foreign key for table `customer`
        $this->dropForeignKey(
            'fk-invoice-customerId',
            'invoice'
        );

        // drops index for column `customerId`
        $this->dropIndex(
            'idx-invoice-customerId',
            'invoice'
        );

        $this->dropTable('{{%invoice}}');
    }
}
