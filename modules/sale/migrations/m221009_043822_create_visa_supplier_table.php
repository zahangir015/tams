<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%visa_supplier}}`.
 */
class m221009_043822_create_visa_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%visa_supplier}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'motherVisaSupplierId' => $this->integer(11)->null(),
            'visaId' => $this->integer(11)->notNull(),
            'billId' => $this->integer(11)->null(),
            'countryId' => $this->integer()->notNull(),
            'supplierId' => $this->integer(11)->notNull(),
            'supplierRef' => $this->string()->notNull(),
            'paxName' => $this->string()->notNull(),
            'issueDate' => $this->date()->notNull(),
            'refundRequestDate' => $this->date()->null(),
            'type' => "ENUM('New', 'Refund', 'Refund Requested') NOT NULL" ,
            'serviceDetails' => $this->string()->null(),
            'quantity' => $this->integer()->notNull(),
            'unitPrice' => $this->double()->notNull(),
            'costOfSale' => $this->double()->notNull(),
            'securityDeposit' => $this->double()->null(),
            'paidAmount' => $this->double()->defaultValue(0),
            'paymentStatus' => "ENUM('Full Paid','Partially Paid','Due','Refund Adjustment') DEFAULT 'Due'",
            'status' => $this->boolean()->defaultValue(1),
        ]);

        // creates index for column `visaId`
        $this->createIndex(
            'idx-visa-supplier-visaId',
            'visa_supplier',
            'visaId'
        );

        // add foreign key for table `visaId`
        $this->addForeignKey(
            'fk-visa-supplier-visaId',
            'visa_supplier',
            'visaId',
            'visa',
            'id',
            'CASCADE'
        );

        // creates index for column `supplierId`
        $this->createIndex(
            'idx-visa-supplier-supplierId',
            'visa_supplier',
            'supplierId'
        );

        // add foreign key for table `supplier`
        $this->addForeignKey(
            'fk-visa-supplier-supplierId',
            'visa_supplier',
            'supplierId',
            'supplier',
            'id',
            'CASCADE'
        );

        // creates index for column `billId`
        $this->createIndex(
            'idx-visa-supplier-billId',
            'visa_supplier',
            'billId'
        );

        // add foreign key for table `bill`
        $this->addForeignKey(
            'fk-visa-supplier-billId',
            'visa_supplier',
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
        // drops foreign key for table `visa`
        $this->dropForeignKey(
            'fk-visa-supplier-visaId',
            'visa_supplier'
        );

        // drops index for column `visaId`
        $this->dropIndex(
            'idx-visa-supplier-visaId',
            'visa_supplier'
        );

        // drops foreign key for table `supplier`
        $this->dropForeignKey(
            'fk-visa-supplier-supplierId',
            'visa_supplier'
        );

        // drops index for column `supplierId`
        $this->dropIndex(
            'idx-visa-supplier-supplierId',
            'visa_supplier'
        );

        // drops foreign key for table `bill`
        $this->dropForeignKey(
            'fk-visa-supplier-billId',
            'visa_supplier'
        );

        // drops index for column `billId`
        $this->dropIndex(
            'idx-visa-supplier-billId',
            'visa_supplier'
        );

        $this->dropTable('{{%visa_supplier}}');
    }
}
