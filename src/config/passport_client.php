<?php
return [
    'default' => 'service',

    'service' => [
        'base_uri' => env('PASSPORT_BASE_URI', ''),

        //授权码授权
        'authorize_grant' => [
            'client_id' => env('PASSPORT_CLIENT_ID', ''),
            'redirect_uri' => env('PASSPORT_REDIRECT_URI', 'http://example.com/callback'),
            'scope' => env('PASSPORT_SCOPE', ''),
        ],
        // 机器授权
        'machine_grant' => [

        ],
        // 密码授权
        'password_grant' => [],
        // 个人授权
        'personal_grant' => [],
    ]

];