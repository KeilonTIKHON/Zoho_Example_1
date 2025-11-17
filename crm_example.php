<?php
require 'token_manager.php';
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use GuzzleHttp\Client;

$token = getAccessToken();
$client = new Client();

$response = $client->get($_ENV['API_URL'] . '/crm/v2/Leads', [
    'headers' => [
        'Authorization' => 'Zoho-oauthtoken ' . $token
    ]
]);

echo "<pre>";
print_r(json_decode($response->getBody(), true));
echo "</pre>";
