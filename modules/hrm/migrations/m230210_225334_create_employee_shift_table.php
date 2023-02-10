<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_shift}}`.
 */
class m230210_225334_create_employee_shift_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_shift}}', [
            'id' => $this->primaryKey(), 'uid' => $this->string(36)->notNull()->unique(),
            'departmentId' => $this->integer(11)->notNull(),
            'shiftId' => $this->integer(11)->notNull(),
            'employeeId' => $this->integer(11)->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `departmentId`
        $this->createIndex(
            'idx-employee-shift-departmentId',
            'employee_shift',
            'departmentId'
        );

        // add foreign key for table `department`
        $this->addForeignKey(
            'fk-employee-shift-departmentId',
            'employee_shift',
            'departmentId',
            'department',
            'id',
            'CASCADE'
        );

        // creates index for column `shiftId`
        $this->createIndex(
            'idx-employee-shift-shiftId',
            'employee_shift',
            'shiftId'
        );

        // add foreign key for table `shift`
        $this->addForeignKey(
            'fk-employee-shift-shiftId',
            'employee_shift',
            'shiftId',
            'shift',
            'id',
            'CASCADE'
        );

        // creates index for column `employeeId`
        $this->createIndex(
            'idx-employee-shift-employeeId',
            'employee_shift',
            'employeeId'
        );

        // add foreign key for table `employee`
        $this->addForeignKey(
            'fk-employee-shift-employeeId',
            'employee_shift',
            'employeeId',
            'employee',
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
            'fk-employee-shift-departmentId',
            'employee_shift',
        );

        // drop index for column `departmentId`
        $this->dropIndex(
            'idx-employee-shift-departmentId',
            'employee_shift',
        );


        // drop foreign key for table `shift`
        $this->dropForeignKey(
            'fk-employee-shift-shiftId',
            'employee_shift',
        );

        // drop index for column `shiftId`
        $this->dropIndex(
            'idx-employee-shift-shiftId',
            'employee_shift',
        );

        // drop foreign key for table `employee`
        $this->dropForeignKey(
            'fk-employee-shift-employeeId',
            'employee_shift',
        );

        // drop index for column `employeeId`
        $this->dropIndex(
            'idx-employee-shift-employeeId',
            'employee_shift',
        );

        $this->dropTable('{{%employee_shift}}');
    }
}
