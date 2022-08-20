<?php

return [
    'bsVersion' => '4.x',
    //'bsDependencyEnabled' => false,
    'hail812/yii2-adminlte3' => [
        'pluginMap' => [
            'sweetalert2' => [
                'css' => 'sweetalert2-theme-bootstrap-4/bootstrap-4.min.css',
                'js' => 'sweetalert2/sweetalert2.min.js'
            ],
            'toastr' => [
                'css' => ['toastr/toastr.min.css'],
                'js' => ['toastr/toastr.min.js']
            ],
        ]
        ],
        'dateFormatInView' => 'd-m-Y',
    'dateTimeFormatInView' => 'd-m-Y H:i:s',
];
