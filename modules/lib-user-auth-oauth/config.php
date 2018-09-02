<?php

return [
    '__name' => 'lib-user-auth-oauth',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/lib-user-auth-oauth.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/lib-user-auth-oauth' => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'lib-user' => NULL
            ],
            [
                'lib-model' => NULL
            ],
            [
                'api-auth-oauth' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'LibUserAuthOauth\\Server' => [
                'type' => 'file',
                'base' => 'modules/lib-user-auth-oauth/server'
            ],
            'LibUserAuthOauth\\Model' => [
                'type' => 'file',
                'base' => 'modules/lib-user-auth-oauth/model'
            ],
            'LibUserAuthOauth\\Library' => [
                'type' => 'file',
                'base' => 'modules/lib-user-auth-oauth/library'
            ]
        ],
        'files' => []
    ],
    'server' => [
        'lib-user-auth-oauth' => [
            'OAuth ext' => 'LibUserAuthOauth\\Server\\PHP::oauth'
        ]
    ],
    'libUser' => [
        'authorizers' => [
            'oauth' => 'LibUserAuthOauth\\Library\\Authorizer'
        ]
    ]
];