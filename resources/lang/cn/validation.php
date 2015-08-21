<?php

return [

    'custom' => [
        'phone' => [
            'required'  => '请输入手机号',
            'unique'    => '手机号已存在，请重新输入',
            'exists'    => '账号不存在',
        ],

        'device'    => [
            'required'  => '设备号不能为空'
        ],

        'user_name' => [
            'required'      => '请输入用户名',
            'length'        => '用户名长度为:min-:max个字符',
            'unique'        => '用户名已存在，请重新输入'
        ],

        'password'  => [
            'required'      => '请输入密码',
            'length'        => '密码长度为:min-:max个字符',
            'confirmed'     => '两次密码不一致',
        ],

        'now_password'  => [
            'required'      => '请输入当前密码',
            'password'      => '原密码错误',
        ],

        'sms_code'  => [
            'required'      => '请输入验证码',
            'sms_code'      => '验证码错误'
        ]
    ],

    'phone'         => '手机格式不正确',
    'login_failed'  => '登录失败，用户名或密码错误',

    'user_password' => '密码错误',
    'oauth_phone'   => '手机号错误，请输入您的手机号',
    'uname_deny'    => '用户名不能含有非法字符',
    'user_deny' => '该账号已被禁用',

    'sms'   => [
        'expiration_time'   => '距离下次获取短信还有:second秒'
    ]
];