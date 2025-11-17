<?php

return [

    'paths' => [
        'admin/*',
        'api/*',
        'sanctum/csrf-cookie',
    ],

    'allowed_origins' => [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'https://scmlogisticapps.klgsys.com',
    ],

    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],

    'supports_credentials' => true,
];
