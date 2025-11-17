<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use com\zoho\crm\api\dc\EUDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\api\authenticator\store\FileStore;
use com\zoho\crm\api\UserSignature;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$store = new FileStore(__DIR__ . '/tokens/zcrm_token.txt');

$user = new UserSignature($_ENV['ZOHO_USER_EMAIL']);

$token = (new OAuthBuilder())
    ->clientId($_ENV['CLIENT_ID'])
    ->clientSecret($_ENV['CLIENT_SECRET'])
    ->refreshToken($_ENV['REFRESH_TOKEN'])
    ->build();

$environment = EUDataCenter::PRODUCTION();
(new InitializeBuilder())
    ->user($user)      
    ->environment($environment)
    ->token($token)
    ->store($store)
    ->initialize();