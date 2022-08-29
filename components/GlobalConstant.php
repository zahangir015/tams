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

    const ALL_TICKET_TYPE = [
        'New' => 'New',
        'Reissue' => 'Reissue',
        'Refund' => 'Refund',
        'EMD Voucher' => 'EMD Voucher',
        'ADM' => 'ADM',
        'Refund Requested' => 'Refund Requested',
        'Deportee' => 'Deportee'
    ];

    const TICKET_TYPE_FOR_CREATE = [
        'New' => 'New',
        'Reissue' => 'Reissue',
        'EMD Voucher' => 'EMD Voucher',
        'Deportee' => 'Deportee',
        'ADM' => 'ADM',
    ];

    const TICKET_TYPE_FOR_REFUND = [
        'Refund' => 'Refund',
        'Refund Requested' => 'Refund Requested',
    ];

    const PAX_TYPE = ['A' => 'Adult', 'C' => 'Child', 'I' => 'Infant'];

    const PAX_TYPE_INT = ['Adult', 'Child', 'Infant'];
    const BD_AIRPORTS = ['DAC', 'CGP', 'CXB', 'ZYL', 'RJH', 'BZL', 'JSR', 'SPD', 'IRD'];
    const TRIP_TYPE = ['One Way' => 'One Way', 'Return' => 'Return'];
    const BOOKING_TYPE = ['Offline', 'Online'];
}