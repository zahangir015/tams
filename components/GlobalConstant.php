<?php
namespace app\components;

final class GlobalConstant
{
    CONST ACTIVE_STATUS = 1;
    CONST INACTIVE_STATUS = 0;

    CONST DEFAULT_STATUS = [
        1 => 'ACTIVE',
        0 => 'INACTIVE'
    ];

    CONST SUPPLIER_TYPE = [
        1 => 'Prepaid',
        0 => 'Postpaid'
    ];
}