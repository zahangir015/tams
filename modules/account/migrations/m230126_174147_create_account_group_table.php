<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%account_group}}`.
 */
class m230126_174147_create_account_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%account_group}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'accountTypeId' => $this->integer(11)->notNull(),
            'name' => $this->string(175)->notNull(),
            'code' => $this->string(10)->notNull()->unique(),
            'status' => $this->tinyInteger()->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'createdBy' => $this->integer(11)->notNull(),
            'updatedAt' => $this->integer(11)->null(),
            'updatedBy' => $this->integer()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%account_group}}');
    }
}
