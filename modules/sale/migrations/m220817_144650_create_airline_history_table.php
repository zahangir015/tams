<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%airline_history}}`.
 */
class m220817_144650_create_airline_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%airline_history}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'airlineId' => $this->integer(11)->notNull(),
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

        // creates index for column `airlineId`
        $this->createIndex(
            'idx-airline-history-airline-id',
            'airline_history',
            'airlineId'
        );

        // add foreign key for table `airline`
        $this->addForeignKey(
            'fk-airline-history-airline-id',
            'airline_history',
            'airlineId',
            'airline',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `airline`
        $this->dropForeignKey(
            'fk-airline-history-airline-id',
            'airline_history'
        );

        // drops index for column `airlineId`
        $this->dropIndex(
            'idx-airline-history-airline-id',
            'airline_history'
        );

        $this->dropTable('{{%airline_history}}');
    }
}
