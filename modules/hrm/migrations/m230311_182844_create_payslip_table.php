<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payslip}}`.
 */
class m230311_182844_create_payslip_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payslip}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->string(36)->notNull()->unique(),
            'employeeId' => $this->integer()->notNull(),
            'month' => $this->integer(2)->notNull(),
            'year' => $this->integer(4)->notNull(),
            'gross' => $this->double()->notNull(),
            'tax' => $this->double()->notNull()->defaultValue(0),
            'lateFine' => $this->double()->defaultValue(0),
            'totalAdjustment' => $this->double()->defaultValue(0),
            'totalDeduction' => $this->double()->defaultValue(0),
            'totalPaid' => $this->double()->notNull(),
            'paymentMode' => "ENUM('Bank Transfer','Cheque','Cash')",
            'processStatus' => $this->boolean()->defaultValue(0),
            'remarks' => $this->string(255),
            'status' => $this->boolean()->notNull()->defaultValue(1),
            'createdBy' => $this->integer(11)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedBy' => $this->integer(11)->null(),
            'updatedAt' => $this->integer()->null(),
        ]);

        $this->createIndex(
            'idx-payslip-employeeId',
            'payslip',
            'employeeId'
        );

        $this->addForeignKey(
            'fk-payslip-employeeId',
            'payslip',
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
            'fk-payslip-employeeId',
            'payslip'
        );

        $this->dropIndex(
            'idx-payslip-employeeId',
            'payslip'
        );

        $this->dropTable('{{%payslip}}');
    }
}
