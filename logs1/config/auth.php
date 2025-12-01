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
        'vendor' => [
            'driver' => 'session',
            'provider' => 'main_vendors',
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
            'model' => App\Models\Main\User::class,
        ],
        'main_vendors' => [
            'driver' => 'eloquent',
            'model' => App\Models\Main\Vendor::class,
        ],
    ],

    'passwords' => [
        'sws_users' => [
            'provider' => 'sws_users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'main_vendors' => [
            'provider' => 'main_vendors',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];