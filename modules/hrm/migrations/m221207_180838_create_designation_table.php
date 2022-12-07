<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%designation}}`.
 */
class m221207_180838_create_designation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%designation}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'parentId' => $this->integer(11)->null(),
            'departmentId' => $this->integer(11)->notNull(),
            'name' => $this->string(120)->notNull()->unique(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
            'updatedAt' => $this->integer()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%designation}}');
    }
}
