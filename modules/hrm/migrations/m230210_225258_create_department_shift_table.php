<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%department_shift}}`.
 */
class m230210_225258_create_department_shift_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%department_shift}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'departmentId' => $this->integer(11)->notNull(),
            'shiftId' => $this->integer(11)->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `departmentId`
        $this->createIndex(
            'idx-department-shift-departmentId',
            'department_shift',
            'departmentId'
        );

        // add foreign key for table `department`
        $this->addForeignKey(
            'fk-department-shift-departmentId',
            'department_shift',
            'departmentId',
            'department',
            'id',
            'CASCADE'
        );

        // creates index for column `shiftId`
        $this->createIndex(
            'idx-department-shift-shiftId',
            'department_shift',
            'shiftId'
        );

        // add foreign key for table `shift`
        $this->addForeignKey(
            'fk-department-shift-shiftId',
            'department_shift',
            'shiftId',
            'shift',
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
            'fk-department-shift-departmentId',
            'department_shift',
        );

        // drop index for column `departmentId`
        $this->dropIndex(
            'idx-department-shift-departmentId',
            'department_shift',
        );


        // drop foreign key for table `shift`
        $this->dropForeignKey(
            'fk-department-shift-shiftId',
            'department_shift',
        );

        // drop index for column `shiftId`
        $this->dropIndex(
            'idx-department-shift-shiftId',
            'department_shift',
        );

        $this->dropTable('{{%department_shift}}');
    }
}
