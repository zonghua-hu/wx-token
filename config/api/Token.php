<?php

/**
 * 喂车内部子系统 api 定义
 */
return [
    'config' => [
        'server' => 'https://api.weixin.qq.com',
    ],
    'api' => [
        'component_token.post' => [
            'path' => '/cgi-bin/component/api_component_token',
            'method' => 'POST',
        ],
        'authorizer_component_token.post' => [
            'path' => '/cgi-bin/component/api_authorizer_token?component_access_token={componentToken}',
            'method' => 'POST',
        ],
        'developer_token.get' => [
            'path' => '/cgi-bin/token?grant_type=client_credential&appid={appId}&secret={appSecret}',
            'method' => 'GET',
        ],
    ]
];
