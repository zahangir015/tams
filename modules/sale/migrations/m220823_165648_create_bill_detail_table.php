<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bill_details}}`.
 */
class m220823165648_create_bill_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bill_detail}}', [
            'id' => $this->primaryKey(),
            'billId' => $this->integer()->notNull(),
            'refId' => $this->integer()->notNull(),
            'refModel' => $this->string(150)->notNull(),
            'paidAmount' => $this->double()->defaultValue(0),
            'dueAmount' => $this->double()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(1),
        ]);

        // creates index for column `billId`
        $this->createIndex(
            'idx-bill-detail-billId',
            'bill_detail',
            'billId'
        );

        // creates index for column `refId`
        $this->createIndex(
            'idx-bill-detail-refId',
            'bill_detail',
            'refId'
        );

        // creates index for column `refModel`
        $this->createIndex(
            'idx-bill-detail-refModel',
            'bill_detail',
            'refModel'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `invoiceNumber`
        $this->dropIndex(
            'idx-bill-detail-billId',
            'bill_detail'
        );

        // drops index for column `refId`
        $this->dropIndex(
            'idx-bill-detail-refId',
            'bill_detail'
        );

        // drops index for column `refModel`
        $this->dropIndex(
            'idx-bill-detail-refModel',
            'bill_detail'
        );

        $this->dropTable('{{%bill_detail}}');
    }
}
