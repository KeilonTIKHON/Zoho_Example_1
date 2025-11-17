<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use com\zoho\crm\api\dc\EUDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\api\authenticator\store\FileStore;
use com\zoho\crm\api\UserSignature;

// Загружаем .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
//var_dump($dotenv);
// Файл для хранения токена
$store = new FileStore(__DIR__ . '/tokens/zcrm_token.txt');

// UserSignature — email пользователя Zoho CRM
$user = new UserSignature($_ENV['ZOHO_USER_EMAIL']);

// Создание OAuth токена через OAuthBuilder
$token = (new OAuthBuilder())
    ->clientId($_ENV['CLIENT_ID'])
    ->clientSecret($_ENV['CLIENT_SECRET'])
    ->refreshToken($_ENV['REFRESH_TOKEN'])
    ->build();

// Инициализация SDK
$environment = EUDataCenter::PRODUCTION();
(new InitializeBuilder())
    ->user($user)       // <--- обязательное исправление
    ->environment($environment)
    ->token($token)
    ->store($store)
    ->initialize();