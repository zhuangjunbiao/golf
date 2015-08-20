<?php

return [

    'api_domain'    => env('API_DOMAIN'),
    'admin_domain'  => env('ADMIN_DOMAIN'),

    // 头像主机
    'avatar_host'   => env('AVATAR_HOST'),

    // 短信超时时间(分钟)
    'sms_out_time'  => intval(env('SMS_OUT_TIME', 30)),

    // 重复获取短信时间(分钟)
    'sms_gain_time' => intval(env('SMS_GAIN_TIME', 1)),

    'password_prefix'   => md5('golf'),

    'clients'   => [
        'android',
        'ios'
    ],

    'apps'      => [
        1   => 'web',
        2   => 'android',
        3   => 'ios',
        4   => 'comment'
    ],

    'rbac'      => [
        'default_gateway'   => 'auth/login'
    ]
];
