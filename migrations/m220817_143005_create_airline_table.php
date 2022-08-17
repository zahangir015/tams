<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%airline}}`.
 */
class m220817_143005_create_airline_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%airline}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'supplierId' => $this->integer(11)->notNull(),
            'code' => $this->string(10)->notNull()->unique(),
            'name' => $this->string(150)->notNull(),
            'commission' => $this->float()->defaultValue(0),
            'incentive' => $this->float()->defaultValue(0),
            'govTax' => $this->float()->defaultValue(0),
            'serviceCharge' => $this->float()->defaultValue(0),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `supplierId`
        $this->createIndex(
            'idx-airline-supplier-id',
            'airline',
            'supplierId'
        );

        // add foreign key for table `supplier`
        $this->addForeignKey(
            'fk-airline-supplier-id',
            'airline',
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
        // drops foreign key for table `supplier`
        $this->dropForeignKey(
            'fk-airline-supplier-id',
            'airline'
        );

        // drops index for column `supplierId`
        $this->dropIndex(
            'idx-airline-supplier-id',
            'airline'
        );

        $this->dropTable('{{%airline}}');
    }
}
