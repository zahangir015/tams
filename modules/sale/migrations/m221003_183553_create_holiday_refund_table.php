<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%holiday_refund}}`.
 */
class m221003_183553_create_holiday_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%holiday_refund}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'holidayId' => $this->integer()->notNull(),
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

        // creates index for column `holidayId`
        $this->createIndex(
            'idx-holiday-refund-holidayId',
            'holiday_refund',
            'holidayId'
        );

        // creates index for column `refundTransactionId`
        $this->createIndex(
            'idx-holiday-refund-refundTransactionId',
            'holiday_refund',
            'refundTransactionId'
        );

        // add foreign key for table `refundTransaction`
        $this->addForeignKey(
            'fk-holiday-refund-refundTransactionId',
            'holiday_refund',
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
        // drops index for column `holidayId`
        $this->dropIndex(
            'idx-holiday-refund-holidayId',
            'holiday_refund'
        );

        // drops foreign key for table `refundTransaction`
        $this->dropForeignKey(
            'fk-holiday-refund-refundTransactionId',
            'holiday_refund'
        );

        // drops index for column `refundTransactionId`
        $this->dropIndex(
            'idx-holiday-refund-refundTransactionId',
            'holiday_refund'
        );

        $this->dropTable('{{%holiday_refund}}');
    }
}
