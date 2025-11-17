<<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use GuzzleHttp\Client;

if (!isset($_GET['code'])) {
    exit('No code received.');
}

$code = $_GET['code'];
$client = new Client();

$response = $client->post($_ENV['ACCOUNTS_URL'] . '/oauth/v2/token', [
    'form_params' => [
        'grant_type' => 'authorization_code',
        'client_id' => $_ENV['CLIENT_ID'],
        'client_secret' => $_ENV['CLIENT_SECRET'],
        'redirect_uri' => $_ENV['REDIRECT_URI'],
        'code' => $code,
    ]
]);

$data = json_decode($response->getBody(), true);

if (!empty($data['refresh_token'])) {
    updateEnv([
        'REFRESH_TOKEN' => $data['refresh_token'],
        'ACCESS_TOKEN' => $data['access_token'],
        'TOKEN_EXPIRY' => time() + $data['expires_in'],
    ]);
    echo "Tokens saved successfully. You can now use Zoho API.";
} else {
    echo "Failed to retrieve tokens: ";
    print_r($data);
}

function updateEnv(array $vars) {
    $path = __DIR__ . '/.env';
    $contents = file_get_contents($path);
    foreach ($vars as $key => $value) {
        if (preg_match("/^$key=.*$/m", $contents)) {
            $contents = preg_replace("/^$key=.*$/m", "$key=$value", $contents);
        } else {
            $contents .= "\n$key=$value";
        }
    }
    file_put_contents($path, $contents);
}