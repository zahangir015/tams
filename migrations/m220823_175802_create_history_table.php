<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%history}}`.
 */
class m220823_175802_create_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%history}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'tableName' => $this->string(150)->notNull(),
            'tableId' => $this->integer()->notNull(),
            'tableData' => $this->text()->notNull(),
            'snapshot' => $this->timestamp()->notNull(),
            'action' => "ENUM('update', 'delete') DEFAULT 'update'",
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%history}}');
    }
}
