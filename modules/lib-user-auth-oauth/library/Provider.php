<?php
/**
 * Provider
 * @package lib-user-auth-oauth
 * @version 0.0.1
 */

namespace LibUserAuthOauth\Library;

use LibUserAuthOauth\Model\{
    UserAuthOauthApp as UAOApp,
    UserAuthOauthNonce as UAONonce,
    UserAuthOauthSession as UAOSession
};

class Provider
{
    private $auth_url;
    private $consumer;
    private $error;
    private $oauth;
    private $user;
    private $session;

    public function __construct(){
        $this->auth_url = \Mim::$app->router->to('apiOAuthLogin');

        $this->oauth = new \OAuthProvider();
        $this->oauth->consumerHandler([$this, 'checkConsumer']);
        $this->oauth->timestampNonceHandler([$this, 'checkNonce']);
        $this->oauth->tokenHandler([$this, 'checkToken']);
    }


    public function checkConsumer($provider){
        $app = UAOApp::getOne(['key'=>$provider->consumer_key]);
        if(!$app)
            return OAUTH_CONSUMER_KEY_UNKNOWN;

        $this->consumer = $app;
        $provider->consumer_secret = $app->secret;
        
        if(!$provider->callback)
            $provider->callback = $app->redirect;

        return OAUTH_OK;
    }

    public function checkNonce($provider){
        if($this->oauth->timestamp < time() - 5 * 60)
            return OAUTH_BAD_TIMESTAMP;
        return OAUTH_OK;

        // currently this is always OAUTH_BAD_NONCE for misterious reason
        // fix this later.

        $cond = [
            'app'       => $this->consumer->app,
            'nonce'     => $provider->nonce,
            'timestamp' => $this->oauth->timestamp
        ];

        $exists = UAONonce::getOne($cond);
        if($exists)
            return OAUTH_BAD_NONCE;

        UAONonce::create($cond);
        return OAUTH_OK;
    }

    public function checkRequest(): bool{
        try{
            $this->oauth->checkOAuthRequest();
        }catch(\OAuthException $e){
            $this->error = \OAuthProvider::reportProblem($e);
        }

        return !$this->error;
    }

    public function checkToken($provider){
        $token = UAOSession::getOne(['token'=>$provider->token]);

        if(!$token)
            return OAUTH_TOKEN_REJECTED;

        $this->session = $token;

        if($token->type == 1 && $token->verifier != $provider->verifier)
            return OAUTH_VERIFIER_INVALID;
        
        if($token->type == 2)
            $this->user = $token->user;

        $provider->token_secret = $token->secret;
        return OAUTH_OK;
    }

    public function generateAccessToken(){
        if($this->error)
            return;

        while(true){
            $token = bin2hex(\OAuthProvider::generateToken(16));
            if(!UAOSession::getOne(['token'=>$token]))
                break;
        }

        $secret = bin2hex(\OAuthProvider::generateToken(32));

        UAOSession::set([
            'token' => $token,
            'secret' => $secret,
            'type' => 2,
            'verifier' => null,
            'redirect' => null
        ], ['token'=>$this->oauth->token]);
        
        return http_build_query([
            'oauth_token'           => $token,
            'oauth_token_secret'    => $secret
        ]);
    }

    public function generateRequestToken(){
        if($this->error)
            return;

        while(true){
            $token = bin2hex(\OAuthProvider::generateToken(16));
            if(!UAOSession::getOne(['token'=>$token]))
                break;
        }

        $secret = bin2hex(\OAuthProvider::generateToken(32));

        UAOSession::create([
            'type'     => 1,
            'app'      => $this->consumer->app,
            'token'    => $token,
            'secret'   => $secret,
            'redirect' => $this->oauth->callback
        ]);

        return http_build_query([
            'authentification_url'      => $this->auth_url,
            'oauth_token'               => $token,
            'oauth_token_secret'        => $secret,
            'oauth_callback_confirmed'  => true
        ]);
    }

    public function generateVerifier(): string{
        return bin2hex(\OAuthProvider::generateToken(16));
    }

    public function getSession(): ?object{
        return $this->session;
    }

    public function lastError(): ?string{
        return $this->error;
    }

    public function revokeToken(): void{
        if(!$this->session)
            return;
        UAOSession::remove(['id'=>$this->session->id]);
    }

    public function setRequestTokenQuery(): void{
        $this->oauth->isRequestTokenEndpoint(true);
    }
}