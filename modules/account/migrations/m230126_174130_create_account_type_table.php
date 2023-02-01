<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%account_type}}`.
 */
class m230126_174130_create_account_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%account_type}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'name' => $this->string(150)->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'createdAt' => $this->integer(11)->notNull(),
            'updatedAt' => $this->integer(11)->null(),
            'createdBy' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
        ]);

        // creates index for column `name`
        $this->createIndex(
            'idx-account-type-name',
            'account_type',
            'name'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `refModel`
        $this->dropIndex(
            'idx-account-type-name',
            'account_type'
        );

        $this->dropTable('{{%account_type}}');
    }
}
