<?php

$host = 'http://site.mim/api/auth/oauth';
$req_token_url = $host . '/request_token';
$acc_token_url = $host . '/access_token';
$callback_url  = 'http://localhost/oauth/';

$app_key    = 'appkey';
$app_secret = 'appsecret';

$oauth = new Oauth($app_key, $app_secret);

session_start();

// check if request is an error
if(isset($_GET['oauth_problem'])){
    session_unset();
    echo 'Error: ' . $_GET['oauth_problem'];
    exit;
}

// step 1: request token
if(!isset($_GET['oauth_token']) && !isset($_SESSION['request_secret'])){
    $request_token = $oauth->getRequestToken($req_token_url, $callback_url);

    // check for error
    if(isset($request_token['oauth_problem'])){
        session_unset();
        echo 'Error: ' . $request_token['oauth_problem'];
        exit;
    }

    $_SESSION['request_secret'] = $request_token['oauth_token_secret'];

    // build redirect url for user login page
    $next = $request_token['authentification_url'] . '?oauth_token=' . $request_token['oauth_token'];

    header('Location: ' . $next);
}

// step 2: access token
if(isset($_GET['oauth_token']) && isset($_SESSION['request_secret']) && !isset($_SESSION['access_token'])){
    $oauth->setToken($_GET['oauth_token'], $_SESSION['request_secret']);
    
    $verifier_token = $_GET['verifier_token'];

    $access_token = $oauth->getAccessToken($acc_token_url, null, $verifier_token);

    // check for error
    if(isset($access_token['oauth_problem'])){
        session_unset();
        echo 'Error: ' . $access_token['oauth_problem'];
        exit;
    }

    $_SESSION['access_token'] = $access_token['oauth_token'];
    $_SESSION['access_secret'] = $access_token['oauth_token_secret'];

}

// step 3: use the api
$oauth->setToken($_SESSION['access_token'], $_SESSION['access_secret']);
$oauth->fetch("http://site.mim/");

$result = $oauth->getLastResponse();

echo $result;