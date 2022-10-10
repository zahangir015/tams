<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bill}}`.
 */
class m220823_165647_create_bill_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bill}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'supplierId' => $this->integer(11)->notNull(),
            'billNumber' => $this->string(64)->notNull()->unique(),
            'date' => $this->date()->notNull(),
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

        // creates index for column `date`
        $this->createIndex(
            'idx-bill-date',
            'bill',
            'date'
        );

        // creates index for column `billNumber`
        $this->createIndex(
            'idx-bill-billNumber',
            'bill',
            'billNumber'
        );

        // creates index for column `supplierId`
        $this->createIndex(
            'idx-bill-supplierId',
            'bill',
            'supplierId'
        );

        // add foreign key for table `supplier`
        $this->addForeignKey(
            'fk-bill-supplierId',
            'bill',
            'supplierId',
            'supplier',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `date`
        $this->dropIndex(
            'idx-bill-date',
            'bill'
        );

        // drops index for column `billNumber`
        $this->dropIndex(
            'idx-bill-billNumber',
            'bill'
        );

        // drops foreign key for table `supplier`
        $this->dropForeignKey(
            'fk-bill-airlineId',
            'bill'
        );

        // drops index for column `supplierId`
        $this->dropIndex(
            'idx-bill-airlineId',
            'bill'
        );

        $this->dropTable('{{%bill}}');
    }
}
