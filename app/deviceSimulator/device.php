<?php

// Show all errors in CLI
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting device simulator...\n";

$apiUrl = "http://localhost/telemetryDataProcessing2/public/api/telemetry";

$payload = [
    'device_id'          => 'DEVICE001',
    'switch1'            => rand(0, 1),
    'switch2'            => rand(0, 1),
    'switch3'            => rand(0, 1),
    'switch4'            => rand(0, 1),
    'fan'                => rand(0, 1),
    'device_temperature' => rand(20, 45),
    'last_key_entered'   => chr(rand(65, 90)),
];

echo "Payload to send:\n";
print_r($payload);

$ch = curl_init($apiUrl);
if ($ch === false) {
    die("Failed to initialize cURL\n");
}

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Optional: timeout so it doesn't hang forever
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$error    = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "\n=== RESULT ===\n";
if ($error) {
    echo "cURL error: $error\n";
} else {
    echo "HTTP status: $httpCode\n";
    echo "Server response: $response\n";
}
