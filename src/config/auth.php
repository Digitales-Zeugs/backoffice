<?php

return [
    'defaults' => [
        'guard' => 'web'
    ],
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'custom',
            'model' => App\User::class,
        ],
    ],

    'password_timeout' => 10800
];
