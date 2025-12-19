<?php

require 'vendor/autoload.php';

$apiUrl = 'http://127.0.0.1:8000/api/heartbeat';
$apiToken = 'token-online-01'; // Matches the 'online' device from Seeder
$hostname = 'DESKTOP-ONLINE-01';

function sendHeartbeat($url, $token, $hostname)
{
    $data = [
        'hostname' => $hostname,
        'api_token' => $token,
        'status' => 'online',
        'hardware_summary' => [
            'cpu_usage_percent' => rand(10, 90), // Random CPU usage
            'ram_used_percent' => rand(30, 60),
            'ram_total_gb' => 16,
            'disk_used_percent' => 45,
        ],
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['code' => $httpCode, 'response' => $response];
}

echo "Starting Agent Simulation for $hostname...\n";
echo "Press Ctrl+C to stop.\n\n";

while (true) {
    $result = sendHeartbeat($apiUrl, $apiToken, $hostname);

    $timestamp = date('H:i:s');
    if ($result['code'] == 200) {
        echo "[$timestamp] Heartbeat sent successfully. Response: ".$result['response']."\n";
    } else {
        echo "[$timestamp] Error sending heartbeat. Code: {$result['code']}\n";
    }

    // Wait 5 seconds before next heartbeat
    sleep(5);
}
