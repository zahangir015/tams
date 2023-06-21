<?php

namespace app\modules\support;

class SupportConstant
{
    const QUERY_SOURCE = [
        'Website' => 'Website',
        'Facebook' => 'Facebook',
        'LinkedIn' => 'LinkedIn',
        'Phone' => 'Phone',
        'Email' => 'Email',
        'WhatsApp' => 'WhatsApp'
    ];

    const QUERY_STATUS = [
        0 => 'Ignored',
        1 => 'Placed',
        2 => 'Processing',
        3 => 'Successfully Completed',
        4 => 'Failed',
    ];
}