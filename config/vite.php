<?php

return [
    'build_path' => 'build/', // 🟢 kasih tahu Laravel manifest ada di sini
    'dev_server' => [
        'url' => env('VITE_DEV_SERVER_URL', null),
    ],
];
