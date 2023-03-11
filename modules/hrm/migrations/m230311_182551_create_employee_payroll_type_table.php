<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_payroll_type}}`.
 */
class m230311_182551_create_employee_payroll_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_payroll_type_detail}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'employeePayrollId' => $this->integer()->notNull(),
            'payrollTypeId' => $this->integer()->notNull(),
            'amount' => $this->double()->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        // creates index for column `employeePayrollId`
        $this->createIndex(
            'idx-employee-payroll-type-detail-employeePayrollId',
            'employee_payroll_type_detail',
            'employeePayrollId'
        );

        // add foreign key for table `employee_payroll`
        $this->addForeignKey(
            'fk-employee-payroll-type-detail-employeePayrollId',
            'employee_payroll_type_detail',
            'employeePayrollId',
            'employee_payroll',
            'id',
            'CASCADE'
        );

        // creates index for column `payrollTypeId`
        $this->createIndex(
            'idx-employee-payroll-type-detail-payrollTypeId',
            'employee_payroll_type_detail',
            'payrollTypeId'
        );

        // add foreign key for table `payroll_type`
        $this->addForeignKey(
            'fk-employee-payroll-type-detail-payrollTypeId',
            'employee_payroll_type_detail',
            'payrollTypeId',
            'payroll_type',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `employee_payroll`
        $this->dropForeignKey(
            'fk-employee-payroll-type-detail-employeePayrollId',
            'employee_payroll_type_detail'
        );

        // drops index for column `employeePayrollId`
        $this->dropIndex(
            'idx-employee-payroll-type-detail-employeePayrollId',
            'employee_payroll_type_detail'
        );

        // drops foreign key for table `payroll_type`
        $this->dropForeignKey(
            'fk-employee-payroll-type-detail-PayrollTypeId',
            'employee_payroll_type_detail'
        );

        // drops index for column `PayrollTypeId`
        $this->dropIndex(
            'idx-employee-payroll-type-detail-PayrollTypeId',
            'employee_payroll_type_detail'
        );

        $this->dropTable('{{%employee_payroll_type}}');
    }
}
