<?php
return [
    'default' => 'service',

    'service' => [
        'base_uri' => env('PASSPORT_CLIENT_BASE_URI', 'http://example.com'),

        'client_id' => env('PASSPORT_CLIENT_ID', ''),
        'client_secret' => env('PASSPORT_CLIENT_SECRET', ''),
        'query' => env('PASSPORT_CLIENT_QUERY', '/oauth/token'),

        //授权码授权
        'authorize_grant' => [
            'redirect_uri' => env('PASSPORT_CLIENT_AUTHORIZE_REDIRECT_URI', 'http://example.com/callback'),
            'scope' => env('PASSPORT_CLIENT_AUTHORIZE_SCOPE', ''),
        ],
        // 机器授权
        'machine_grant' => [
            'scope' => env('PASSPORT_CLIENT_MACHINE_SCOPE', ''),
        ],
        // 密码授权
        'password_grant' => [
            'scope' => env('PASSPORT_CLIENT_PASSWORD_SCOPE', ''),
        ],
    ],

    //自定义响应数据的处理，可配置一个匿名函数,函数可用$this 指向是 XsPkg\PassportClient\Http\HttpResponse 响应实例
    'response_handle' => null,

];