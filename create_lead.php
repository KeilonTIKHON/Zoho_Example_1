<?php
require 'vendor/autoload.php';
require 'token_manager.php';

use GuzzleHttp\Client;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request');
}

$token = getAccessToken();
$client = new Client();

$leadData = [
    "data" => [[
        "First_Name" => $_POST['First_Name'] ?? '',
        "Last_Name"  => $_POST['Last_Name'] ?? '',
        "Email"      => $_POST['Email'] ?? '',
        "Company"    => $_POST['Company'] ?? 'Individual',
        "Lead_Status" => "New",
        "Webhook_URL" => $_ENV['WEBHOOK_RETURN_URL'] // для связи
    ]]
];

$response = $client->post($_ENV['API_URL'] . '/crm/v6/Leads', [
    'headers' => [
        'Authorization' => 'Zoho-oauthtoken ' . $token,
        'Content-Type'  => 'application/json'
    ],
    'json' => $leadData
]);

$result = json_decode($response->getBody(), true);
echo "<h3>Lead created successfully!</h3>";
echo "<pre>";
print_r($result);
echo "</pre>";