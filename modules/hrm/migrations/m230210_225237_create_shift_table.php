<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shift}}`.
 */
class m230210_225237_create_shift_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shift}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'title' => $this->string(150)->notNull()->unique(),
            'entryTime' => $this->time()->notNull(),
            'exitTime' => $this->time()->notNull(),
            'totalHours' => $this->time()->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shift}}');
    }
}
