<?php
namespace app\components;

final class GlobalConstant
{
    const ACTIVE_STATUS = 1;
    const INACTIVE_STATUS = 0;

    const DEFAULT_STATUS = [
        1 => 'Active',
        0 => 'Inactive'
    ];

    const SUPPLIER_TYPE = [
        1 => 'Prepaid',
        0 => 'Postpaid'
    ];

    const CUSTOMER_CATEGORY = [
        'B2C' => 'Business To Customer',
        'B2B' => 'Business To Business',
        'B2E' => 'Business To Enterprise',
    ];

    const YES_NO = [
        1 => 'Yes',
        0 => 'No'
    ];
}