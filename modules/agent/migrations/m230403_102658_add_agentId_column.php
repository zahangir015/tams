<?php

use yii\db\Migration;

/**
 * Class m230403_102658_add_agentId_column
 */
class m230403_102658_add_agentId_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\app\modules\sale\models\Airline::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\models\Attachment::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Attendance::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\BankAccount::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\Bill::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Branch::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\ChartOfAccount::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\models\Company::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\Customer::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Department::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\DepartmentShift::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Designation::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\LeaveAllocation::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\EmployeePayroll::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\EmployeeShift::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\Expense::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\ExpenseCategory::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\ExpenseSubCategory::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\models\History::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\holiday\Holiday::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\holiday\HolidayCategory::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\hotel\Hotel::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\Invoice::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\Journal::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\LeaveApplication::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\LeaveApprovalPolicy::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\LeaveType::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\Ledger::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\PayrollType::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Payslip::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\Provider::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\PublicHoliday::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\RefundTransaction::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Roster::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Shift::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\StarCategory::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\Supplier::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\ticket\Ticket::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\Transaction::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\admin\models\User::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\visa\Visa::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Weekend::tableName(), 'agentId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\YearlyLeaveAllocation::tableName(), 'agentId', $this->integer(11)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230403_102658_add_agentId_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230403_102658_add_agentId_column cannot be reverted.\n";

        return false;
    }
    */
}
