<?php

return [
    'defaults' => [
        'guard' => 'sws',
        'passwords' => 'sws_users',
    ],

    'guards' => [
        'sws' => [
            'driver' => 'session',
            'provider' => 'sws_users',
        ],
        'api' => [
            'driver' => 'token',
            'provider' => 'sws_users',
            'hash' => false,
        ],
    ],

    'providers' => [
        'sws_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\SWS\User::class,
        ],
    ],

    'passwords' => [
        'sws_users' => [
            'provider' => 'sws_users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];