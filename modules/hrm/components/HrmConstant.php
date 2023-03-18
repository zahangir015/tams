<?php
namespace app\modules\hrm\components;

final class HrmConstant
{
    const PROBATION = ['1' => 'Yes', '0' => 'No'];
    const BLOOD_GROUP = ['A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+' => 'AB+', 'AB-' => 'AB-'];
    const RELIGION = ['Islam' => 'Islam', 'Hindu' => 'Hindu', 'Buddhist' => 'Buddhist', 'Christian' => 'Christian',];
    const MARITAL_STATUS = [1 => 'Married', 2 => 'Unmarried'];
    const GENDER = [1 => 'Male', 2 => 'Female'];
    const DAYS = ['Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday', 'Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday'];
    const NUMBER_OF_DAYS = ['0.5' => 'Half Day', '1' => 'Full Day', '2' => 'Two Days', '3' => 'Three Days', '4' => 'Four Days', '5' => 'Five Days'];
    const APPROVAL_STATUS = ['Pending' => 'Pending', 'Manager Approved' => 'Manager Approved', 'Approved' => 'Approved', 'Cancelled' => 'Cancelled'];
    const FLEXIBLE_ENTRY_TIME_IN_MINUTE = 30;
    const PAYROLL_CATEGORY = ['Salary' => 'Salary', 'Allowance' => 'Allowance'];

    const AMOUNT_TYPE = ['Percentage', 'Amount'];
    const CALCULATING_METHOD = ['Addition', 'Subtraction'];
    const PAYMENT_MODE = ['Bank Transfer' => 'Bank Transfer', 'Cheque' => 'Cheque', 'Cash' => 'Cash',];
}