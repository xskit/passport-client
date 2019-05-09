<?php
return [
    'default' => 'service',

    'service' => [
        'base_uri' => env('PASSPORT_CLIENT_BASE_URI', 'http://example.com'),

        'query' => env('PASSPORT_CLIENT_QUERY', '/oauth/token'),

        //授权码授权
        'authorize_grant' => [
            'client_id' => env('PASSPORT_CLIENT_AUTHORIZE_ID', ''),
            'redirect_uri' => env('PASSPORT_CLIENT_AUTHORIZE_REDIRECT_URI', 'http://example.com/callback'),
            'scope' => env('PASSPORT_CLIENT_AUTHORIZE_SCOPE', ''),
        ],
        // 机器授权
        'machine_grant' => [
            'client_id' => env('PASSPORT_CLIENT_MACHINE_ID', ''),
            'client_secret' => env('PASSPORT_CLIENT_MACHINE_SECRET', ''),
            'scope' => env('PASSPORT_CLIENT_MACHINE_SCOPE', ''),
        ],
        // 密码授权
        'password_grant' => [
            'client_id' => env('PASSPORT_CLIENT_PASSWORD_ID', ''),
            'client_secret' => env('PASSPORT_CLIENT_PASSWORD_SECRET', ''),
            'scope' => env('PASSPORT_CLIENT_PASSWORD_SCOPE', ''),
        ],
        // 获取授权码时，GuzzleHttp 选项配置
        'guzzle_options' => [

        ],
    ],

    // 自定义响应数据的处理
    // 可配置一个匿名函数,函数可用$this 指向是 XsPkg\PassportClient\Http\HttpResponse 响应实例
    // 该函数接收一个 Psr\Http\Message\ResponseInterface 响应实例
    'response_handle' => null,

];