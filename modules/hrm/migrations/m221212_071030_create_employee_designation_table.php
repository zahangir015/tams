<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_designation}}`.
 */
class m221212_071030_create_employee_designation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_designation}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'employeeId' => $this->integer()->notNull(),
            'departmentId' => $this->integer()->notNull(),
            'designationId' => $this->integer()->notNull(),
            'branchId' => $this->integer()->notNull(),
            'startDate' => $this->date()->notNull(),
            'endDate' => $this->date()->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer()->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `employeeId`
        $this->createIndex(
            'idx-employee-designation-employeeId',
            'employee_designation',
            'employeeId'
        );

        // add foreign key for table `employee`
        $this->addForeignKey(
            'fk-employee-designation-employeeId',
            'employee_designation',
            'employeeId',
            'employee',
            'id',
            'CASCADE'
        );

        // creates index for column `departmentId`
        $this->createIndex(
            'idx-employee-designation-departmentId',
            'employee_designation',
            'departmentId'
        );

        // add foreign key for table `department`
        $this->addForeignKey(
            'fk-employee-designation-departmentId',
            'employee_designation',
            'departmentId',
            'department',
            'id',
            'CASCADE'
        );

        // creates index for column `designationId`
        $this->createIndex(
            'idx-employee-designation-designationId',
            'employee_designation',
            'designationId'
        );

        // add foreign key for table `designation`
        $this->addForeignKey(
            'fk-employee-designation-designationId',
            'employee_designation',
            'designationId',
            'designation',
            'id',
            'CASCADE'
        );

        // creates index for column `branchId`
        $this->createIndex(
            'idx-employee-designation-branchId',
            'employee_designation',
            'branchId'
        );

        // add foreign key for table `branch`
        $this->addForeignKey(
            'fk-employee-designation-branchId',
            'employee_designation',
            'branchId',
            'branch',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop foreign key for table `employee`
        $this->dropForeignKey(
            'fk-employee-designation-employeeId',
            'employee_designation',
        );
        // drop index for column `employeeId`
        $this->dropIndex(
            'idx-employee-designation-employeeId',
            'employee_designation',
        );

        // drop foreign key for table `department`
        $this->dropForeignKey(
            'fk-employee-designation-departmentId',
            'employee_designation',
        );
        // drop index for column `departmentId`
        $this->dropIndex(
            'idx-employee-designation-departmentId',
            'employee_designation',
        );

        // drop foreign key for table `designation`
        $this->dropForeignKey(
            'fk-employee-designation-designationId',
            'employee_designation',
        );
        // drop index for column `designationId`
        $this->dropIndex(
            'idx-employee-designation-designationId',
            'employee_designation',
        );

        // drop foreign key for table `branch`
        $this->dropForeignKey(
            'fk-employee-designation-branchId',
            'employee_designation',
        );
        // drop index for column `branchId`
        $this->dropIndex(
            'idx-employee-designation-branchId',
            'employee_designation',
        );

        $this->dropTable('{{%employee_designation}}');
    }
}
