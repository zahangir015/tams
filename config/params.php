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
    'uploadOptions' => [
        'path' => 'uploads', //set as bootstrap in common/config/bootstrap.php
        'maxFileSize' => 1024 * 1024 * 2, // 2 MB
        'maxFileCount' => 1, // at a time 1 file can be uploaded,
        'allowedFileTypes' => [
            'pdf' => 'application/pdf',
        ],
        'allowedImageTypes' => [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpe' => 'image/jpeg',
            'gif' => 'image/gif',
            'png' => 'image/png',
        ],
    ],
];
