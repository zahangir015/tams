<?php

use yii\db\Migration;

/**
 * Class m230403_164649_drop_agentId_column
 */
class m230403_164649_drop_agentId_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\Airline::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Airline::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\models\Attachment::getTableSchema()->name).'-agencyId', \app\models\Attachment::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Attendance::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Attendance::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\BankAccount::getTableSchema()->name).'-agencyId', \app\modules\account\models\BankAccount::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\Bill::getTableSchema()->name).'-agencyId', \app\modules\account\models\Bill::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Branch::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Branch::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\ChartOfAccount::getTableSchema()->name).'-agencyId', \app\modules\account\models\ChartOfAccount::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\models\Company::getTableSchema()->name).'-agencyId', \app\models\Company::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\Customer::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Customer::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Department::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Department::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\DepartmentShift::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\DepartmentShift::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Designation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Designation::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\LeaveAllocation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveAllocation::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\EmployeePayroll::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\EmployeePayroll::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\EmployeeShift::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\EmployeeShift::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\Expense::getTableSchema()->name).'-agencyId', \app\modules\account\models\Expense::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\ExpenseCategory::getTableSchema()->name).'-agencyId', \app\modules\account\models\ExpenseCategory::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\ExpenseSubCategory::getTableSchema()->name).'-agencyId', \app\modules\account\models\ExpenseSubCategory::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\models\History::getTableSchema()->name).'-agencyId', \app\models\History::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\holiday\Holiday::getTableSchema()->name).'-agencyId', \app\modules\sale\models\holiday\Holiday::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\holiday\HolidayCategory::getTableSchema()->name).'-agencyId', \app\modules\sale\models\holiday\HolidayCategory::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\hotel\Hotel::getTableSchema()->name).'-agencyId', \app\modules\sale\models\hotel\Hotel::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\Invoice::getTableSchema()->name).'-agencyId', \app\modules\account\models\Invoice::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\Journal::getTableSchema()->name).'-agencyId', \app\modules\account\models\Journal::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\LeaveApplication::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveApplication::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\LeaveApprovalPolicy::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveApprovalPolicy::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\LeaveType::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveType::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\Ledger::getTableSchema()->name).'-agencyId', \app\modules\account\models\Ledger::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\PayrollType::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\PayrollType::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Payslip::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Payslip::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\Provider::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Provider::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\PublicHoliday::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\PublicHoliday::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\RefundTransaction::getTableSchema()->name).'-agencyId', \app\modules\account\models\RefundTransaction::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Roster::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Roster::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Shift::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Shift::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\StarCategory::getTableSchema()->name).'-agencyId', \app\modules\sale\models\StarCategory::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\Supplier::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Supplier::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\ticket\Ticket::getTableSchema()->name).'-agencyId', \app\modules\sale\models\ticket\Ticket::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\Transaction::getTableSchema()->name).'-agencyId', \app\modules\account\models\Transaction::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\admin\models\User::getTableSchema()->name).'-agencyId', \app\modules\admin\models\User::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\visa\Visa::getTableSchema()->name).'-agencyId', \app\modules\sale\models\visa\Visa::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Weekend::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Weekend::tableName());
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\YearlyLeaveAllocation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\YearlyLeaveAllocation::tableName());*/


        // index drop
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\sale\models\Airline::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Airline::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\models\Attachment::getTableSchema()->name).'-agencyId', \app\models\Attachment::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Attendance::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Attendance::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\account\models\BankAccount::getTableSchema()->name).'-agencyId', \app\modules\account\models\BankAccount::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\account\models\Bill::getTableSchema()->name).'-agencyId', \app\modules\account\models\Bill::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Branch::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Branch::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\account\models\ChartOfAccount::getTableSchema()->name).'-agencyId', \app\modules\account\models\ChartOfAccount::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\models\Company::getTableSchema()->name).'-agencyId', \app\models\Company::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\sale\models\Customer::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Customer::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Department::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Department::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\DepartmentShift::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\DepartmentShift::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Designation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Designation::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\LeaveAllocation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveAllocation::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\EmployeePayroll::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\EmployeePayroll::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\EmployeeShift::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\EmployeeShift::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\account\models\Expense::getTableSchema()->name).'-agencyId', \app\modules\account\models\Expense::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\account\models\ExpenseCategory::getTableSchema()->name).'-agencyId', \app\modules\account\models\ExpenseCategory::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\account\models\ExpenseSubCategory::getTableSchema()->name).'-agencyId', \app\modules\account\models\ExpenseSubCategory::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\models\History::getTableSchema()->name).'-agencyId', \app\models\History::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\sale\models\holiday\Holiday::getTableSchema()->name).'-agencyId', \app\modules\sale\models\holiday\Holiday::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\sale\models\holiday\HolidayCategory::getTableSchema()->name).'-agencyId', \app\modules\sale\models\holiday\HolidayCategory::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\sale\models\hotel\Hotel::getTableSchema()->name).'-agencyId', \app\modules\sale\models\hotel\Hotel::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\account\models\Invoice::getTableSchema()->name).'-agencyId', \app\modules\account\models\Invoice::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\account\models\Journal::getTableSchema()->name).'-agencyId', \app\modules\account\models\Journal::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\LeaveApplication::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveApplication::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\LeaveApprovalPolicy::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveApprovalPolicy::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\LeaveType::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveType::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\account\models\Ledger::getTableSchema()->name).'-agencyId', \app\modules\account\models\Ledger::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\PayrollType::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\PayrollType::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Payslip::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Payslip::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\sale\models\Provider::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Provider::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\PublicHoliday::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\PublicHoliday::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\account\models\RefundTransaction::getTableSchema()->name).'-agencyId', \app\modules\account\models\RefundTransaction::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Roster::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Roster::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Shift::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Shift::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\sale\models\StarCategory::getTableSchema()->name).'-agencyId', \app\modules\sale\models\StarCategory::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\sale\models\Supplier::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Supplier::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\sale\models\ticket\Ticket::getTableSchema()->name).'-agencyId', \app\modules\sale\models\ticket\Ticket::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\account\models\Transaction::getTableSchema()->name).'-agencyId', \app\modules\account\models\Transaction::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\admin\models\User::getTableSchema()->name).'-agencyId', \app\modules\admin\models\User::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\sale\models\visa\Visa::getTableSchema()->name).'-agencyId', \app\modules\sale\models\visa\Visa::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Weekend::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Weekend::tableName());
        $this->dropIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\YearlyLeaveAllocation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\YearlyLeaveAllocation::tableName());


        $this->dropColumn(\app\modules\sale\models\Airline::tableName(), 'agencyId');
        $this->dropColumn(\app\models\Attachment::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\Attendance::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\account\models\BankAccount::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\account\models\Bill::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\Branch::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\account\models\ChartOfAccount::tableName(), 'agencyId');
        $this->dropColumn(\app\models\Company::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\sale\models\Customer::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\Department::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\DepartmentShift::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\Designation::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\LeaveAllocation::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\EmployeePayroll::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\EmployeeShift::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\account\models\Expense::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\account\models\ExpenseCategory::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\account\models\ExpenseSubCategory::tableName(), 'agencyId');
        $this->dropColumn(\app\models\History::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\sale\models\holiday\Holiday::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\sale\models\holiday\HolidayCategory::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\sale\models\hotel\Hotel::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\account\models\Invoice::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\account\models\Journal::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\LeaveApplication::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\LeaveApprovalPolicy::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\LeaveType::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\account\models\Ledger::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\PayrollType::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\Payslip::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\sale\models\Provider::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\PublicHoliday::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\account\models\RefundTransaction::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\Roster::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\Shift::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\sale\models\StarCategory::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\sale\models\Supplier::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\sale\models\ticket\Ticket::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\account\models\Transaction::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\admin\models\User::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\sale\models\visa\Visa::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\Weekend::tableName(), 'agencyId');
        $this->dropColumn(\app\modules\hrm\models\YearlyLeaveAllocation::tableName(), 'agencyId');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230403_164649_drop_agentId_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230403_164649_drop_agentId_column cannot be reverted.\n";

        return false;
    }
    */
}
