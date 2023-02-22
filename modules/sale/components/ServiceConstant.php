<?php

namespace app\modules\sale\components;

final class ServiceConstant
{
    const INVOICE_DETAIL_REISSUE_STATUS = 3;
    const INVOICE_DETAIL_REFUND_STATUS = 2;

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

    const ALL_SERVICE_TYPE = [
        'New' => 'New',
        'Refund' => 'Refund',
        'Refund Requested' => 'Refund Requested',
    ];

    const SERVICE_TYPE_FOR_CREATE = [
        'New' => 'New',
        'Refund' => 'Refund'
    ];

    const PAX_TYPE = ['A' => 'Adult', 'C' => 'Child', 'I' => 'Infant'];
    const PAX_TYPE_INT = ['Adult', 'Child', 'Infant'];
    const BD_AIRPORTS = ['DAC', 'CGP', 'CXB', 'ZYL', 'RJH', 'BZL', 'JSR', 'SPD', 'IRD'];
    const TRIP_TYPE = ['One Way' => 'One Way', 'Return' => 'Return'];
    const BOOKING_TYPE = ['Offline', 'Online'];
    const FLIGHT_TYPE = ['Domestic', 'International', 'SOTO'];
    const PAYMENT_STATUS = ['Due' => 'Due', 'Partially Paid' => 'Partially Paid', 'Full Paid' => 'Full Paid'];
    const REFUND_TYPE = ['NO SHOW' => 'NO SHOW', 'NOT NO SHOW' => 'NOT NO SHOW', 'TAX REFUND' => 'TAX REFUND', 'HALF PORTION REFUND' => 'HALF PORTION REFUND', 'FULL REFUND' => 'FULL REFUND', 'VOID' => 'VOID', 'HALF PORTION TAX REFUND' => 'HALF PORTION TAX REFUND'];
    const REFUND_STATUS = ['Refund Submitted' => 'Refund Submitted','Refund Received' => 'Refund Received','Refund Paid' => 'Refund Paid','Refund Adjusted' => 'Refund Adjusted'];
    const REFUND_MEDIUM = ['GDS' => 'GDS', 'BSP' => 'BSP', 'SUPPLIER' => 'SUPPLIER'];
    const REFUND_METHOD = ['Credit/Debit Card' => 'Credit/Debit Card', 'Bank Account' => 'Bank Account', 'Refund Adjustment' => 'Refund Adjustment', 'Cash' => 'Cash', 'MFS' => "MFS"];
    const TYPE = ['New' => 'New', 'Reissue' => 'Reissue', 'Refund' => 'Refund', 'EMD Voucher' => 'EMD Voucher', 'Refund Requested' => 'Refund Requested'];
    const VISA_PROCESS_STATUS = [
        'Document Received',
        'Passport Received',
        'Payment',
        'Review',
        'Submitted',
        'Collection',
        'Application Delivered',
        'Passport Delivered - Success',
        'Passport Delivered - Rejected'
    ];
    const OTHER_SERVICE_REFUND_STATUS = ['Full Paid' => 'Full Paid', 'Partially Paid' => 'Partially Paid', 'Due' => 'Due', 'Refund Adjustment' => 'Refund Adjustment'];
    const STATE = ['Full Refund' => 2, 'Partial Refund' => 1, 'Not Refunded' => 0];

    const SEAT_CLASS = ['Economy Class' => 'Economy Class', 'Premium Economy Class' => 'Premium Economy Class', 'Business Class' => 'Business Class', 'First Class' => 'First Class'];
    const REFUND_POLICY = ['Refundable & Changeable' => 'Refundable & Changeable', 'Non-Refundable & Non Changeable' => 'Non-Refundable & Non Changeable', 'Non-refundable but Changeable' => 'Non-refundable but Changeable'];

}