<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

session_start();

$_SESSION['user_id'] = 1;

require __DIR__ .'/../vendor/autoload.php';

$api = new ApiContext(
    new OAuthTokenCredential(
        'AfyIiULIt-FD7ZwpHlyb0njvcljfVgUfYXNMYDiPhw-n7uAxSYKmkVcTL75zDiB4Ood8BImqjpf3Pith',
        'EB5sOuDTM7RtnxOjmP4cda2K6C0tYGi3rtpka_J5WNKfOx14ayVqqm3Dou2wxOs8CCI_xum3u13AdK3_'
    )
);

$api->setConfig([
    'mode' => 'sandbox',
    'http.ConnectionTimeOut' => 30,
    'log.LogEnabled' => false,
    'log.FileName' => '',
    'log.LogLevel' => 'FINE',
    'validation.level' => 'log'
]);


//$db = new PDO('mysql:host=localhost;dbname=paypal', 'root', '');

//$user = $db->prepare("
//SELECT * FROM users
//WHERE id = :user_id
//");
//
//
//
//$user->execute(['user_id' => $_SESSION['user_id']]);
//
//$user = $user->fetchObject();
//
