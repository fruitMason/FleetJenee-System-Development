<?php

return [


    'default' => env('GOIL_SMS_USERNAME', 'null'),

    'business' => [
        'goil' => [
            'username' => env('GOIL_SMS_USERNAME'),
            'password' => env('GOIL_SMS_PASSWORD')
        ],
    ],
];
