<?php

return [
    'email' => [
        'status'     => true,
        'queue'      => 'email_queue',
        'connection' => 'redis',
        'tries'      => 3,
        'sleep'      => 5,
        'timeout'    => 60
    ],

    'sms' => [
        'status'     => true,
        'queue'      => 'sms_queue',
        'connection' => 'redis',
        'tries'      => 3,
        'sleep'      => 5,
        'timeout'    => 60
    ],

    'robot' => [
        'exception_robot' => [
            'status'    => true,
            'signature' => env('EXCEPTION_SIGNATURE',''),
            'webhook'   => env('EXCEPTION_ROBOT_WEBHOOK',''),
            'title'     => 'www.crm.com项目异常汇报',
            'subtitle'  => '系统异常，请相关开发人员及时处理bug'
        ]
    ]
];
