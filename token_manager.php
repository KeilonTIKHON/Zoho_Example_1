<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use GuzzleHttp\Client;

function getAccessToken() {
    if (time() < intval($_ENV['TOKEN_EXPIRY']) && !empty($_ENV['ACCESS_TOKEN'])) {
        return $_ENV['ACCESS_TOKEN'];
    }

    $client = new Client();
    $response = $client->post($_ENV['ACCOUNTS_URL'] . '/oauth/v2/token', [
        'form_params' => [
            'grant_type' => 'refresh_token',
            'client_id' => $_ENV['CLIENT_ID'],
            'client_secret' => $_ENV['CLIENT_SECRET'],
            'refresh_token' => $_ENV['REFRESH_TOKEN']
        ]
    ]);

    $data = json_decode($response->getBody(), true);
    if (!empty($data['access_token'])) {
        updateEnv([
            'ACCESS_TOKEN' => $data['access_token'],
            'TOKEN_EXPIRY' => time() + $data['expires_in']
        ]);
        return $data['access_token'];
    } else {
        throw new Exception("Failed to refresh access token");
    }
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