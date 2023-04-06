<?php

use yii\db\Migration;

/**
 * Class m230403_102658_add_agencyId_column
 */
class m230403_102658_add_agentId_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\app\modules\sale\models\Airline::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\models\Attachment::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Attendance::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\BankAccount::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\Bill::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Branch::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\ChartOfAccount::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\models\Company::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\Customer::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Department::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\DepartmentShift::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Designation::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\LeaveAllocation::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\EmployeePayroll::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\EmployeeShift::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\Expense::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\ExpenseCategory::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\ExpenseSubCategory::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\models\History::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\holiday\Holiday::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\holiday\HolidayCategory::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\hotel\Hotel::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\Invoice::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\Journal::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\LeaveApplication::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\LeaveApprovalPolicy::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\LeaveType::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\Ledger::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\PayrollType::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Payslip::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\Provider::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\PublicHoliday::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\RefundTransaction::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Roster::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Shift::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\StarCategory::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\Supplier::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\sale\models\ticket\Ticket::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\account\models\Transaction::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\admin\models\User::tableName(), 'agencyId', $this->integer(11)->null());
        $this->addColumn(\app\modules\sale\models\visa\Visa::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\Weekend::tableName(), 'agencyId', $this->integer(11)->notNull());
        $this->addColumn(\app\modules\hrm\models\YearlyLeaveAllocation::tableName(), 'agencyId', $this->integer(11)->notNull());

        // index
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\sale\models\Airline::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Airline::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\models\Attachment::getTableSchema()->name).'-agencyId', \app\models\Attachment::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Attendance::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Attendance::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\account\models\BankAccount::getTableSchema()->name).'-agencyId', \app\modules\account\models\BankAccount::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\account\models\Bill::getTableSchema()->name).'-agencyId', \app\modules\account\models\Bill::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Branch::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Branch::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\account\models\ChartOfAccount::getTableSchema()->name).'-agencyId', \app\modules\account\models\ChartOfAccount::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\models\Company::getTableSchema()->name).'-agencyId', \app\models\Company::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\sale\models\Customer::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Customer::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Department::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Department::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\DepartmentShift::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\DepartmentShift::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Designation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Designation::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\LeaveAllocation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveAllocation::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\EmployeePayroll::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\EmployeePayroll::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\EmployeeShift::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\EmployeeShift::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\account\models\Expense::getTableSchema()->name).'-agencyId', \app\modules\account\models\Expense::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\account\models\ExpenseCategory::getTableSchema()->name).'-agencyId', \app\modules\account\models\ExpenseCategory::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\account\models\ExpenseSubCategory::getTableSchema()->name).'-agencyId', \app\modules\account\models\ExpenseSubCategory::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\models\History::getTableSchema()->name).'-agencyId', \app\models\History::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\sale\models\holiday\Holiday::getTableSchema()->name).'-agencyId', \app\modules\sale\models\holiday\Holiday::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\sale\models\holiday\HolidayCategory::getTableSchema()->name).'-agencyId', \app\modules\sale\models\holiday\HolidayCategory::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\sale\models\hotel\Hotel::getTableSchema()->name).'-agencyId', \app\modules\sale\models\hotel\Hotel::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\account\models\Invoice::getTableSchema()->name).'-agencyId', \app\modules\account\models\Invoice::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\account\models\Journal::getTableSchema()->name).'-agencyId', \app\modules\account\models\Journal::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\LeaveApplication::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveApplication::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\LeaveApprovalPolicy::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveApprovalPolicy::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\LeaveType::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveType::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\account\models\Ledger::getTableSchema()->name).'-agencyId', \app\modules\account\models\Ledger::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\PayrollType::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\PayrollType::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Payslip::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Payslip::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\sale\models\Provider::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Provider::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\PublicHoliday::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\PublicHoliday::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\account\models\RefundTransaction::getTableSchema()->name).'-agencyId', \app\modules\account\models\RefundTransaction::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Roster::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Roster::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Shift::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Shift::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\sale\models\StarCategory::getTableSchema()->name).'-agencyId', \app\modules\sale\models\StarCategory::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\sale\models\Supplier::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Supplier::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\sale\models\ticket\Ticket::getTableSchema()->name).'-agencyId', \app\modules\sale\models\ticket\Ticket::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\account\models\Transaction::getTableSchema()->name).'-agencyId', \app\modules\account\models\Transaction::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\admin\models\User::getTableSchema()->name).'-agencyId', \app\modules\admin\models\User::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\sale\models\visa\Visa::getTableSchema()->name).'-agencyId', \app\modules\sale\models\visa\Visa::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\Weekend::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Weekend::tableName(), 'agencyId');
        $this->createIndex('idx-'.str_replace('`', '',\app\modules\hrm\models\YearlyLeaveAllocation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\YearlyLeaveAllocation::tableName(), 'agencyId');


        // foreign key
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\Airline::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Airline::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\models\Attachment::getTableSchema()->name).'-agencyId', \app\models\Attachment::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Attendance::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Attendance::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\BankAccount::getTableSchema()->name).'-agencyId', \app\modules\account\models\BankAccount::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\Bill::getTableSchema()->name).'-agencyId', \app\modules\account\models\Bill::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Branch::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Branch::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\ChartOfAccount::getTableSchema()->name).'-agencyId', \app\modules\account\models\ChartOfAccount::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\models\Company::getTableSchema()->name).'-agencyId', \app\models\Company::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\Customer::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Customer::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Department::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Department::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\DepartmentShift::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\DepartmentShift::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Designation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Designation::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\LeaveAllocation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveAllocation::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\EmployeePayroll::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\EmployeePayroll::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\EmployeeShift::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\EmployeeShift::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\Expense::getTableSchema()->name).'-agencyId', \app\modules\account\models\Expense::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\ExpenseCategory::getTableSchema()->name).'-agencyId', \app\modules\account\models\ExpenseCategory::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\ExpenseSubCategory::getTableSchema()->name).'-agencyId', \app\modules\account\models\ExpenseSubCategory::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\models\History::getTableSchema()->name).'-agencyId', \app\models\History::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\holiday\Holiday::getTableSchema()->name).'-agencyId', \app\modules\sale\models\holiday\Holiday::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\holiday\HolidayCategory::getTableSchema()->name).'-agencyId', \app\modules\sale\models\holiday\HolidayCategory::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\hotel\Hotel::getTableSchema()->name).'-agencyId', \app\modules\sale\models\hotel\Hotel::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\Invoice::getTableSchema()->name).'-agencyId', \app\modules\account\models\Invoice::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\Journal::getTableSchema()->name).'-agencyId', \app\modules\account\models\Journal::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\LeaveApplication::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveApplication::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\LeaveApprovalPolicy::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveApprovalPolicy::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\LeaveType::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\LeaveType::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\Ledger::getTableSchema()->name).'-agencyId', \app\modules\account\models\Ledger::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\PayrollType::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\PayrollType::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Payslip::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Payslip::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\Provider::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Provider::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\PublicHoliday::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\PublicHoliday::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\RefundTransaction::getTableSchema()->name).'-agencyId', \app\modules\account\models\RefundTransaction::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Roster::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Roster::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Shift::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Shift::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\StarCategory::getTableSchema()->name).'-agencyId', \app\modules\sale\models\StarCategory::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\Supplier::getTableSchema()->name).'-agencyId', \app\modules\sale\models\Supplier::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\ticket\Ticket::getTableSchema()->name).'-agencyId', \app\modules\sale\models\ticket\Ticket::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\account\models\Transaction::getTableSchema()->name).'-agencyId', \app\modules\account\models\Transaction::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\admin\models\User::getTableSchema()->name).'-agencyId', \app\modules\admin\models\User::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\sale\models\visa\Visa::getTableSchema()->name).'-agencyId', \app\modules\sale\models\visa\Visa::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\Weekend::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\Weekend::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
        $this->addForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\YearlyLeaveAllocation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\YearlyLeaveAllocation::tableName(), 'agencyId', \app\modules\agent\models\Agency::tableName(), 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // foreign key
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
        $this->dropForeignKey('fk-'.str_replace('`', '',\app\modules\hrm\models\YearlyLeaveAllocation::getTableSchema()->name).'-agencyId', \app\modules\hrm\models\YearlyLeaveAllocation::tableName());

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

        echo "m230403_102658_add_agencyId_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230403_102658_add_agencyId_column cannot be reverted.\n";

        return false;
    }
    */
}
