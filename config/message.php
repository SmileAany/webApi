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
    ]
];
