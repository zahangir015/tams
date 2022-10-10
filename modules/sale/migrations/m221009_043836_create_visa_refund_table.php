<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%visa_refund}}`.
 */
class m221009_043836_create_visa_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%visa_refund}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'visaId' => $this->integer()->notNull(),
            'refundTransactionId' => $this->integer()->null(),
            'refId' => $this->integer()->notNull(),
            'refModel' => $this->string(150)->notNull(),
            'refundRequestDate' => $this->date()->notNull(),
            'refundStatus' => "ENUM('Full Paid','Partially Paid','Due','Refund Adjustment') DEFAULT NULL",
            'refundMedium' => "ENUM('GDS','BSP','Supplier') DEFAULT NULL",
            'refundMethod' => "ENUM('Credit/Debit Card','Bank Account','Refund Adjustment') DEFAULT NULL",
            'supplierRefundCharge' => $this->double()->defaultValue(0),
            'serviceCharge' => $this->double()->defaultValue(0),
            'isRefunded' => $this->boolean()->defaultValue(0),
            'refundDate' => $this->date()->null(),
            'refundedAmount' => $this->double()->null(),
            'remarks' => $this->text(),
            'status' => $this->boolean()->defaultValue(1),
        ]);

        // creates index for column `visaId`
        $this->createIndex(
            'idx-visa-refund-visaId',
            'visa_refund',
            'visaId'
        );

        // creates index for column `refundTransactionId`
        $this->createIndex(
            'idx-visa-refund-refundTransactionId',
            'visa_refund',
            'refundTransactionId'
        );

        // add foreign key for table `refundTransaction`
        $this->addForeignKey(
            'fk-visa-refund-refundTransactionId',
            'visa_refund',
            'refundTransactionId',
            'refund_transaction',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `visaId`
        $this->dropIndex(
            'idx-visa-refund-visaId',
            'visa_refund'
        );

        // drops foreign key for table `refundTransaction`
        $this->dropForeignKey(
            'fk-visa-refund-refundTransactionId',
            'visa_refund'
        );

        // drops index for column `refundTransactionId`
        $this->dropIndex(
            'idx-visa-refund-refundTransactionId',
            'visa_refund'
        );

        $this->dropTable('{{%visa_refund}}');
    }
}
