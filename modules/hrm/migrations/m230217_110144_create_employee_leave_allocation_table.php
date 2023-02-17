<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_leave_allocation}}`.
 */
class m230217_110144_create_employee_leave_allocation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_leave_allocation}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'employeeId' => $this->integer(11)->notNull(),
            'leaveTypeId' => $this->integer(11)->notNull(),
            'year' => $this->integer(4)->notNull(),
            'totalDays' => $this->tinyInteger(1)->notNull(),
            'availedDays' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'remainingDays' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `leaveTypeId`
        $this->createIndex(
            'idx-employee-leave-allocation-leaveTypeId',
            'employee_leave_allocation',
            'leaveTypeId'
        );

        // add foreign key for table `leave_type`
        $this->addForeignKey(
            'fk-employee-leave-allocation-leaveTypeId',
            'employee_leave_allocation',
            'leaveTypeId',
            'leave_type',
            'id',
            'CASCADE'
        );

        // creates index for column `employeeId`
        $this->createIndex(
            'idx-employee-leave-allocation-employeeId',
            'employee_leave_allocation',
            'employeeId'
        );

        // add foreign key for table `employee`
        $this->addForeignKey(
            'fk-employee-leave-allocation-employeeId',
            'employee_leave_allocation',
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
        // drop foreign key for table `leaveType`
        $this->dropForeignKey(
            'fk-employee-leave-allocation-leaveTypeId',
            'employee_leave_allocation',
        );

        // drop index for column `leaveTypeId`
        $this->dropIndex(
            'idx-employee-leave-allocation-leaveTypeId',
            'employee_leave_allocation',
        );

        // drop foreign key for table `employee`
        $this->dropForeignKey(
            'fk-employee-leave-allocation-employeeId',
            'employee_leave_allocation',
        );

        // drop index for column `employeeId`
        $this->dropIndex(
            'idx-employee-leave-allocation-employeeId',
            'employee_leave_allocation',
        );

        $this->dropTable('{{%employee_leave_allocation}}');
    }
}
