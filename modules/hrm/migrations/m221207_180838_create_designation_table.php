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

        // creates index for column `departmentId`
        $this->createIndex(
            'idx-designation-departmentId',
            'designation',
            'departmentId'
        );

        // add foreign key for table `department`
        $this->addForeignKey(
            'fk-designation-departmentId',
            'designation',
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
        // drops foreign key for table `department`
        $this->dropForeignKey(
            'fk-designation-departmentId',
            'designation'
        );

        // drops index for column `departmentId`
        $this->dropIndex(
            'idx-designation-departmentId',
            'designation'
        );

        $this->dropTable('{{%designation}}');
    }
}
