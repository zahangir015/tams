<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payslip_type_detail}}`.
 */
class m230311_182856_create_payslip_type_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payslip_type_detail}}', [
            'id' => $this->primaryKey(),
            'payrollTypeId' => $this->integer()->notNull(),
            'payslipId' => $this->integer()->notNull(),
            'amount' => $this->double()->notNull()->defaultValue(0),
            'calculatedAmount' => $this->double()->notNull()->defaultValue(0),
        ]);

        $this->createIndex(
            'idx-payslip-type-detail-payrollTypeId',
            'payslip_type_detail',
            'payrollTypeId'
        );

        $this->addForeignKey(
            'fk-payslip-type-detail-payrollTypeId',
            'payslip_type_detail',
            'payrollTypeId',
            'payroll_type',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-payslip-type-detail-payslipId',
            'payslip_type_detail',
            'payslipId'
        );

        $this->addForeignKey(
            'fk-payslip-type-detail-payslipId',
            'payslip_type_detail',
            'payslipId',
            'payslip',
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
            'fk-payslip-type-detail-payrollTypeId',
            'payslip_type_detail'
        );

        $this->dropIndex(
            'idx-payslip-type-detail-payrollTypeId',
            'payslip_type_detail'
        );

        $this->dropForeignKey(
            'fk-payslip-type-detail-payslipId',
            'payslip_type_detail'
        );

        $this->dropIndex(
            'idx-payslip-type-detail-payslipId',
            'payslip_type_detail'
        );

        $this->dropTable('{{%payslip_type_detail}}');
    }
}
