<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_payroll}}`.
 */
class m230311_182534_create_employee_payroll_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_payroll}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'employeeId' => $this->integer()->notNull(),
            'gross' => $this->double()->notNull(),
            'tax' => $this->double()->notNull(),
            'paymentMode' => "ENUM('Bank Transfer','Cheque','Cash')",
            'remarks' => $this->string(255)->null(),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        $this->createIndex(
            'idx-employee-payroll-employeeId',
            'employee_payroll',
            'employeeId'
        );

        $this->addForeignKey(
            'fk-employee-payroll-employeeId',
            'employee_payroll',
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
        $this->dropForeignKey(
            'fk-employee-payroll-employeeId',
            'employee_payroll'
        );

        $this->dropIndex(
            'idx-employee-payroll-employeeId',
            'employee_payroll'
        );

        $this->dropTable('{{%employee_payroll}}');
    }
}
