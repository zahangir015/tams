<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction}}`.
 */
class m220823_175047_create_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'transactionNumber' => $this->string(64)->notNull(),
            'refId' => $this->integer()->notNull(),
            'refModel' => $this->string(150)->notNull(),
            'subRefId' => $this->integer()->notNull(),
            'subRefModel' => $this->string(150)->notNull(),
            'bankId' => $this->integer(11)->notNull(),
            'reference' => $this->string()->notNull()->comment('Cheque number/ Payment reference number'),
            'paidAmount' => $this->double()->notNull(),
            'paymentCharge' => $this->double()->notNull(),
            'paymentDate' => $this->date()->notNull(),
            'paymentMode' => "ENUM('Cheque','Cash','POS','Online Payment','Credit/Debit Card','Adjustment','Advance Adjustment')",
            'status' => $this->boolean()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
        ]);

        // creates index for column `bankId`
        $this->createIndex(
            'idx-transaction-bankId',
            'transaction',
            'bankId'
        );

        // add foreign key for table `bank_account`
        $this->addForeignKey(
            'fk-transaction-bankId',
            'transaction',
            'bankId',
            'bank_account',
            'id',
            'CASCADE'
        );

        // creates index for column `transactionNumber`
        $this->createIndex(
            'idx-transaction-transactionNumber',
            'transaction',
            'transactionNumber'
        );

        // creates index for column `refId`
        $this->createIndex(
            'idx-transaction-refId',
            'transaction',
            'refId'
        );

        // creates index for column `refModel`
        $this->createIndex(
            'idx-transaction-refModel',
            'transaction',
            'refModel'
        );

        // creates index for column `subRefId`
        $this->createIndex(
            'idx-transaction-subRefId',
            'transaction',
            'subRefId'
        );

        // creates index for column `subRefModel`
        $this->createIndex(
            'idx-transaction-subRefModel',
            'transaction',
            'subRefModel'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `transactionNumber`
        $this->dropIndex(
            'idx-transaction-transactionNumber',
            'transaction'
        );

        // drops index for column `refId`
        $this->dropIndex(
            'idx-transaction-refId',
            'transaction'
        );

        // drops index for column `refModel`
        $this->dropIndex(
            'idx-transaction-refModel',
            'transaction'
        );

        // drops index for column `subRefId`
        $this->dropIndex(
            'idx-transaction-subRefId',
            'transaction'
        );

        // drops index for column `subRefModel`
        $this->dropIndex(
            'idx-transaction-subRefModel',
            'transaction'
        );

        // drops foreign key for table `bankAccount`
        $this->dropForeignKey(
            'fk-transaction-bankId',
            'transaction'
        );

        // drops index for column `bankId`
        $this->dropIndex(
            'idx-transaction-bankId',
            'transaction'
        );

        $this->dropTable('{{%transaction}}');
    }
}
