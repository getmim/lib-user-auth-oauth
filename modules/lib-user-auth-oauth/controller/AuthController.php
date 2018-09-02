<?php
/**
 * AuthController
 * @package lib-user-auth-oauth
 * @version 0.0.1
 */

namespace LibUserAuthOauth\Controller;

use LibUserAuthOauth\Library\Authorizer;
use LibUserAuthOauth\Model\UserAuthOauthSession as UAOSession;
use LibApp\Model\App;

class AuthController extends \Api\Controller
{
    private function errorView($error){
        $this->res->render('user/auth/oauth/error', [
            'error' => $error
        ]);
        $this->res->send();
    }

    public function requestAction() {
        $provider = Authorizer::getProvider();
        $provider->setRequestTokenQuery();

        if(!$provider->checkRequest())
            $this->res->addContent($provider->lastError());
        else
            $this->res->addContent($provider->generateRequestToken());
        $this->res->send();
    }

    public function accessAction() {
        $provider = Authorizer::getProvider();
        $provider->checkRequest();

        if(!$provider->checkRequest())
            $this->res->addContent($provider->lastError());
        else
            $this->res->addContent($provider->generateAccessToken());
        $this->res->send();
    }

    public function loginAction(){
        $token = $this->req->get('oauth_token');
        if(!$token)
            return $this->errorView('Please provide `oauth_token` parameter');

        $rtoken = UAOSession::getOne(['token'=>$token]);
        if(!$rtoken)
            return $this->errorView('Invalid `oauth_token`');

        $app = App::getOne(['id'=>$rtoken->app]);
        if(!$app)
            return $this->errorView('Invalid application');

        if(!$this->user->isLogin()){
            $router = $this->config->libUserAuthOauth->loginRoute;
            $next = $this->router->to($router, [], [
                'next' => $this->req->url
            ]);

            $this->res->redirect($next);
        }

        if($this->req->getQuery('deny') == 1){
            UAOSession::remove(['id'=>$rtoken->id]);
            return $this->res->redirect($rtoken->redirect . '?oauth_problem=denied_by_user');
        }

        if($this->req->getPost('allow') == 1){
            $provider = Authorizer::getProvider();
            $verifier = $provider->generateVerifier();

            UAOSession::set([
                'verifier' => $verifier,
                'user'     => $this->user->id
            ], ['id'=>$rtoken->id]);

            $next = $rtoken->redirect;
            $sign = false != strstr($next, '?') ? '&' : '?';
            $query = http_build_query([
                'oauth_token' => $rtoken->token,
                'verifier_token' => $verifier
            ]);

            $next.= $sign . $query;

            return $this->res->redirect($next);
        }

        $this->res->render('user/auth/oauth/authorize', [
            'app' => $app,
            'rtoken' => $rtoken
        ]);
        
        return $this->res->send();
    }
}