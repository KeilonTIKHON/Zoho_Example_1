<?php
$data = file_get_contents('php://input');
$decoded = json_decode($data, true);

if (!$decoded) {
    http_response_code(400);
    echo "Invalid payload";
    exit;
}

file_put_contents(__DIR__ . '/webhook_log.txt', date('Y-m-d H:i:s') . " " . json_encode($decoded) . PHP_EOL, FILE_APPEND);

echo "Webhook received:\n";
print_r($decoded);