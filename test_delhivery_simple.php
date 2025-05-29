<?php
// Simple test of Delhivery API with curl

$apiKey = '8e0534aa9d751cad7d08527493cec1f0541c7bd1';
$baseUrl = 'https://staging-express.delhivery.com';

echo "\n🔍 Testing Delhivery API with different methods\n\n";

// Test 1: Basic authentication test
echo "1. Testing with Bearer token:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/c/api/pin-codes/json/?filter_codes=110001');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Accept: application/json'
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   Status: $httpCode\n";
echo "   Response: " . substr($response, 0, 100) . "\n\n";

// Test 2: With Token prefix
echo "2. Testing with Token prefix:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/c/api/pin-codes/json/?filter_codes=110001');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Token ' . $apiKey,
    'Accept: application/json'
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   Status: $httpCode\n";
echo "   Response: " . substr($response, 0, 100) . "\n\n";

// Test 3: As API key header
echo "3. Testing with Api-Token header:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/c/api/pin-codes/json/?filter_codes=110001');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Api-Token: ' . $apiKey,
    'Accept: application/json'
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   Status: $httpCode\n";
echo "   Response: " . substr($response, 0, 100) . "\n";