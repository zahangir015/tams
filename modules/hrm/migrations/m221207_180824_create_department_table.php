<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%department}}`.
 */
class m221207_180824_create_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%department}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'parentId' => $this->integer(11)->null(),
            'name' => $this->string(120)->notNull()->unique(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `name`
        $this->createIndex(
            'idx-department-name',
            'department',
            'name'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `name`
        $this->dropIndex(
            'idx-department-name',
            'department'
        );

        $this->dropTable('{{%department}}');
    }
}
