<?php
/**
 * Authorizer
 * @package lib-user-auth-oauth
 * @version 0.0.1
 */

namespace LibUserAuthOauth\Library;

use LibUserAuthOauth\Library\Provider;
use LibEvent\Library\Event;

class Authorizer
    implements 
        \LibUser\Iface\Authorizer,
        \LibApp\Iface\Authorizer
{

    private static $provider;

    private static $session;

    static function getAppId(): ?int{
        if(!self::$session)
            return null;

        return self::$session->app;
    }

    static function getProvider(): object{
        if(!self::$provider)
            self::$provider = new Provider();
        return self::$provider;
    }

    static function getSession(): ?object {
        return self::$session;
    }

    static function hasScope(string $scope): bool {
        return false;
    }

    static function identify(): ?string {
        $provider = self::getProvider();
        if(!$provider->checkRequest())
            return null;

        $session = $provider->getSession();
        if(!$session)
            return null;
        if($session->type == 1)
            return null;
        if(!$session->user)
            return null;

        $result = [
            'type' => 'oauth',
            'expires' => 0,
            'token' => $session->token,
            'app' => $session->app
        ];

        self::$session = (object)$result;

        return $session->user;
    }

    static function loginById(string $identity): ?array {
        if(module_exists('lib-event'))
            Event::trigger('user:authorized', $identity);
        return null;
    }

    static function logout(): void{
        self::getProvider()->revokeToken();
    }
}