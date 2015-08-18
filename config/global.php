<?php

return [

    'domain'    => env('DOMAIN'),

    'clients'   => [
        'android',
        'ios'
    ],

    'rbac'      => [
        'default_gateway'   => 'auth/login'
    ]
];
