<?php
return [
    'default' => 'service',

    'service' => [
        'base_uri' => env('PASSPORT_BASE_URI', 'http://example.com'),

        'client_id' => env('PASSPORT_CLIENT_ID', ''),
        'client_secret' => env('PASSPORT_CLIENT_SECRET', ''),
        'query' => env('PASSPORT_CLIENT_QUERY', '/oauth/token'),

        //授权码授权
        'authorize_grant' => [
            'redirect_uri' => env('PASSPORT_AUTHORIZE_REDIRECT_URI', 'http://example.com/callback'),
            'scope' => env('PASSPORT_AUTHORIZE_SCOPE', ''),
        ],
        // 机器授权
        'machine_grant' => [
            'scope' => env('PASSPORT_MACHINE_SCOPE', ''),
        ],
        // 密码授权
        'password_grant' => [
            'scope' => env('PASSPORT_PASSWORD_SCOPE', ''),
        ],
    ]

];