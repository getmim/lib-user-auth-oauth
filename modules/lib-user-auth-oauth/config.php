<?php

return [
    '__name' => 'lib-user-auth-oauth',
    '__version' => '0.0.2',
    '__git' => 'git@github.com:getmim/lib-user-auth-oauth.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/lib-user-auth-oauth' => ['install','update','remove'],
        'theme/api/user/auth/oauth'   => ['install','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'lib-user' => NULL
            ],
            [
                'lib-app' => NULL
            ],
            [
                'lib-model' => NULL
            ],
            [
                'api' => NULL
            ],
            [
                'lib-view' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'LibUserAuthOauth\\Controller' => [
                'type' => 'file',
                'base' => 'modules/lib-user-auth-oauth/controller'
            ],
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
    ],
    'routes' => [
        'api' => [
            'apiUserAuthOAuthAccessToken' => [
                'path' => [
                    'value' => '/auth/oauth/access_token'
                ],
                'handler' => 'LibUserAuthOauth\\Controller\\Auth::access',
                'method' => 'POST|GET'
            ],
            'apiUserAuthOAuthRequestToken' => [
                'path' => [
                    'value' => '/auth/oauth/request_token'
                ],
                'handler' => 'LibUserAuthOauth\\Controller\\Auth::request',
                'method' => 'POST|GET'
            ],
            'apiUserAuthOAuthLogin' => [
                'path' => [
                    'value' => '/auth/oauth/login'
                ],
                'handler' => 'LibUserAuthOauth\\Controller\\Auth::login',
                'method' => 'POST|GET'
            ]
        ]
    ],
    'libUserAuthOauth' => [
        'loginRoute' => 'siteLogin'
    ]
];