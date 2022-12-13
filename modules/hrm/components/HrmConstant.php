<?php
namespace app\modules\hrm\components;

final class HrmConstant
{
    const PROBATION = ['1' => 'Yes', '0' => 'No'];
    const BLOOD_GROUP = ['A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+' => 'AB+', 'AB-' => 'AB-'];
    const RELIGION = ['Islam' => 'Islam', 'Hindu' => 'Hindu', 'Buddhist' => 'Buddhist', 'Christian' => 'Christian',];
    const MARITAL_STATUS = ['Married' => 'Married', 'Unmarried' => 'Unmarried'];
    const GENDER = ['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other'];
}