<?php

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['POST'],

    'allowed_origins' => [
        'http://127.0.0.1:8000',
        'http://localhost',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
    ],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];