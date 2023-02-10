<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%weekend}}`.
 */
class m230210_224728_create_weekend_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%weekend}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'departmentId' => $this->integer(11)->notNull(),
            'day' => $this->string(10)->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);
        // creates index for column `departmentId`
        $this->createIndex(
            'idx-weekend-departmentId',
            'weekend',
            'departmentId'
        );

        // add foreign key for table `department`
        $this->addForeignKey(
            'fk-weekend-departmentId',
            'weekend',
            'departmentId',
            'department',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop foreign key for table `department`
        $this->dropForeignKey(
            'fk-weekend-departmentId',
            'weekend',
        );

        // drop index for column `departmentId`
        $this->dropIndex(
            'idx-weekend-departmentId',
            'weekend',
        );

        $this->dropTable('{{%weekend}}');
    }
}
