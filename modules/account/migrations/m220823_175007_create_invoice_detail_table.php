<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoice_details}}`.
 */
class m220823_175007_create_invoice_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice_detail}}', [
            'id' => $this->primaryKey(),
            'invoiceId' => $this->integer()->notNull(),
            'refId' => $this->integer()->notNull(),
            'refModel' => $this->string(150)->notNull(),
            'paidAmount' => $this->double()->defaultValue(0),
            'dueAmount' => $this->double()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1),
        ]);

        // creates index for column `invoiceId`
        $this->createIndex(
            'idx-invoice-detail-invoiceId',
            'invoice_detail',
            'invoiceId'
        );

        // creates index for column `refId`
        $this->createIndex(
            'idx-invoice-detail-refId',
            'invoice_detail',
            'refId'
        );

        // creates index for column `refModel`
        $this->createIndex(
            'idx-invoice-detail-refModel',
            'invoice_detail',
            'refModel'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `invoiceId`
        $this->dropIndex(
            'idx-invoice-detail-invoiceId',
            'invoice_detail'
        );

        // drops index for column `refId`
        $this->dropIndex(
            'idx-invoice-detail-refId',
            'invoice_detail'
        );

        // drops index for column `refModel`
        $this->dropIndex(
            'idx-invoice-detail-refModel',
            'invoice_detail'
        );

        $this->dropTable('{{%invoice_detail}}');
    }
}
