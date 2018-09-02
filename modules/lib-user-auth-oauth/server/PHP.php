<?php
/**
 * Server test
 * @package lib-user-auth-oauth
 * @version 0.0.1
 */

namespace LibUserAuthOauth\Server;

class PHP 
{
    static function oauth(){
        $exists = class_exists('OAuthProvider');

        return [
            'success' => $exists,
            'info' => ''
        ];
    }
}