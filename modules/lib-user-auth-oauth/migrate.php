<?php

return [
    'LibUserAuthOauth\\Model\\UserAuthOauthSession' => [
        'fields' => [
            'id' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'primary_key' => true,
                    'auto_increment' => true
                ]
            ],
            'type' => [
                'type' => 'TINYINT',
                // 1 => Request
                // 2 => Access
                'length' => 1,
                'attrs' => [
                    'unsigned' => true,
                    'null' => false
                ]
            ],
            'app' => [
                'type' => 'INT',
                'attrs' => [
                    'null' => false,
                    'unsigned' => true
                ]
            ],
            'user' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true
                ]
            ],
            'token' => [
                'type' => 'VARCHAR',
                'length' => 32,
                'attrs' => [
                    'null' => false,
                    'unique' => true
                ]
            ],
            'secret' => [
                'type' => 'VARCHAR',
                'length' => 64,
                'attrs' => [
                    'null' => false
                ]
            ],
            'verifier' => [
                'type' => 'VARCHAR',
                'length' => 32,
                'attrs' => []
            ],
            'redirect' => [
                'type' => 'VARCHAR',
                'length' => 250,
                'attrs' => []
            ],
            'created' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP'
                ]
            ]
        ]
    ],
    'LibUserAuthOauth\\Model\\UserAuthOauthNonce' => [
        'fields' => [
            'id' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'primary_key' => true,
                    'auto_increment' => true
                ]
            ],
            'app' => [
                'type' => 'INT',
                'attrs' => [
                    'null' => false,
                    'unsigned' => true
                ]
            ],
            'nonce' => [
                'type' => 'VARCHAR',
                'length' => 250,
                'attrs' => [
                    'null' => false
                ]
            ],
            'timestamp' => [
                'type' => 'VARCHAR',
                'attrs' => [
                    'null' => false
                ]
            ],
            'created' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP'
                ]
            ]
        ]
    ],
    'LibUserAuthOauth\\Model\\UserAuthOauthApp' => [
        'fields' => [
            'id' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'primary_key' => true,
                    'auto_increment' => true 
                ]
            ],
            'app' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'null' => false 
                ]
            ],
            'key' => [
                'type' => 'VARCHAR',
                'length' => 32,
                'attrs' => [
                    'unique' => true,
                    'null' => false
                ]
            ],
            'secret' => [
                'type' => 'VARCHAR',
                'length' => 64,
                'attrs' => [
                    'null' => false
                ]
            ],
            'redirect' => [
                'type' => 'VARCHAR',
                'length' => 250,
                'attrs' => [
                    'null' => false
                ]
            ],
            'created' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP'
                ]
            ]
        ]
    ]
];