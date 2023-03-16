<?php

use app\modules\hrm\models\EmployeePayrollTypeDetail;
use yii\db\Migration;

/**
 * Class m230316_130023_drop_uid_column_createdAt_column_createdBy_column_updatedBy_column_updatedAt_column_from_employee_payroll_type_detail
 */
class m230316_130023_drop_uid_column_createdAt_column_createdBy_column_updatedBy_column_updatedAt_column_from_employee_payroll_type_detail extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn(EmployeePayrollTypeDetail::tableName(), 'uid');
        $this->dropColumn(EmployeePayrollTypeDetail::tableName(), 'createdBy');
        $this->dropColumn(EmployeePayrollTypeDetail::tableName(), 'createdAt');
        $this->dropColumn(EmployeePayrollTypeDetail::tableName(), 'updatedAt');
        $this->dropColumn(EmployeePayrollTypeDetail::tableName(), 'updatedBy');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230316_130023_drop_uid_column_createdAt_column_createdBy_column_updatedBy_column_updatedAt_column_from_employee_payroll_type_detail cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230316_130023_drop_uid_column_createdAt_column_createdBy_column_updatedBy_column_updatedAt_column_from_employee_payroll_type_detail cannot be reverted.\n";

        return false;
    }
    */
}
