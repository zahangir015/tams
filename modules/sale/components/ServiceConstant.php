<?php
namespace app\modules\sale\components;

final class ServiceConstant
{
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
    const FLIGHT_TYPE = ['Domestic', 'International'];
    const PAYMENT_STATUS = ['Due' => 'Due', 'Partially Paid' => 'Partially Paid', 'Full Paid' => 'Full Paid0'];
    const REFUND_STATUS = ['NO SHOW' => 'NO SHOW', 'NOT NO SHOW' => 'NOT NO SHOW', 'TAX REFUND' => 'TAX REFUND', 'HALF PORTION REFUND' => 'HALF PORTION REFUND', 'FULL REFUND' => 'FULL REFUND', 'VOID' => 'VOID', 'HALF PORTION TAX REFUND' => 'HALF PORTION TAX REFUND'];
    const REFUND_MEDIUM = ['GDS' => 'GDS', 'BSP' => 'BSP', 'SUPPLIER' => 'SUPPLIER'];
    const REFUND_METHOD = ['Credit/Debit Card' => 'Credit/Debit Card', 'Bank Account' => 'Bank Account', 'Refund Adjustment' => 'Refund Adjustment'];
    const TYPE = ['New' => 'New', 'Reissue' => 'Reissue', 'Refund' => 'Refund', 'EMD Voucher' => 'EMD Voucher', 'Refund Requested' => 'Refund Requested'];

}