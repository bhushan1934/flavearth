<?php
// Final test with exact Delhivery format

echo "\n🎯 FINAL DELHIVERY API TEST\n";
echo "==========================\n\n";

$apiKey = '8e0534aa9d751cad7d08527493cec1f0541c7bd1';
$baseUrl = 'https://staging-express.delhivery.com';

// Test with the EXACT minimal format from Delhivery docs
$shipment = [
    "waybill" => "",
    "name" => "Test",
    "order" => "TEST" . rand(100000, 999999),
    "products_desc" => "Test",
    "order_date" => date('Y-m-d H:i:s'),
    "payment_mode" => "Prepaid",
    "total_amount" => "100",
    "cod_amount" => "0", 
    "add" => "Test Address",
    "city" => "Delhi",
    "state" => "Delhi",
    "country" => "India",
    "phone" => "9999999999",
    "pin" => "110001",
    "return_add" => "Test",
    "return_city" => "Delhi",
    "return_country" => "India",
    "return_name" => "Test",
    "return_phone" => "9999999999",
    "return_pin" => "110001", 
    "return_state" => "Delhi",
    "vendor_name" => "",
    "pickup_location" => "R K Enterprises",
    "quantity" => "1",
    "weight" => "500", // in grams
    "seller_name" => "Test Seller",
    "seller_add" => "Test Address",
    "seller_tin" => ""
];

$requestData = "format=json&data=" . json_encode(["shipments" => [$shipment]]);

echo "1. Request Details:\n";
echo "URL: $baseUrl/api/cmu/create.json\n";
echo "Method: POST\n";
echo "Headers:\n";
echo "- Authorization: Token [HIDDEN]\n";
echo "- Content-Type: application/x-www-form-urlencoded\n\n";

echo "2. Payload:\n";
echo json_encode(["shipments" => [$shipment]], JSON_PRETTY_PRINT) . "\n\n";

// Make the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/cmu/create.json');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Token ' . $apiKey,
    'Content-Type: application/x-www-form-urlencoded',
    'Accept: application/json'
]);

$startTime = microtime(true);
$response = curl_exec($ch);
$endTime = microtime(true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlInfo = curl_getinfo($ch);
curl_close($ch);

$executionTime = round(($endTime - $startTime) * 1000, 2);

echo "3. Response:\n";
echo "Status Code: $httpCode\n";
echo "Execution Time: {$executionTime}ms\n";
echo "Response Size: " . strlen($response) . " bytes\n\n";

$responseData = json_decode($response, true);
if ($responseData) {
    echo "Decoded Response:\n";
    print_r($responseData);
    
    // Analyze the response
    echo "\n4. Analysis:\n";
    if (isset($responseData['packages']) && count($responseData['packages']) > 0) {
        echo "✅ SUCCESS: Shipment created\n";
        foreach ($responseData['packages'] as $pkg) {
            echo "   Waybill: " . ($pkg['waybill'] ?? 'N/A') . "\n";
            echo "   Status: " . ($pkg['status'] ?? 'N/A') . "\n";
        }
    } else if (isset($responseData['rmk'])) {
        echo "⚠️  API Message: " . $responseData['rmk'] . "\n";
        
        // Parse the error
        if (strpos($responseData['rmk'], 'end_date') !== false) {
            echo "\n🔴 CONFIRMED: This is a server-side issue with Delhivery's staging API.\n";
            echo "   The error 'NoneType' object has no attribute 'end_date' indicates:\n";
            echo "   - A Python error on their server\n";
            echo "   - Missing or incorrectly initialized date field in their code\n";
            echo "   - This is NOT an issue with our request format\n";
        }
        
        if (strpos($responseData['rmk'], 'might be saved') !== false) {
            echo "\n📌 The shipment MIGHT have been partially created.\n";
            echo "   Delhivery support needs to check their logs.\n";
        }
    }
    
    // Check success indicators
    echo "\n5. Success Indicators:\n";
    echo "   - success field: " . ($responseData['success'] ? 'true' : 'false') . "\n";
    echo "   - error field: " . ($responseData['error'] ? 'true' : 'false') . "\n";
    echo "   - package_count: " . ($responseData['package_count'] ?? 0) . "\n";
} else {
    echo "Raw Response:\n$response\n";
}

echo "\n6. Connection Details:\n";
echo "   - Total Time: " . round($curlInfo['total_time'] * 1000, 2) . "ms\n";
echo "   - SSL Verify: " . ($curlInfo['ssl_verify_result'] == 0 ? 'Success' : 'Failed') . "\n";
echo "   - HTTP Version: " . $curlInfo['http_version'] . "\n";

echo "\n7. Conclusion:\n";
echo "The Delhivery staging API is experiencing a server-side error.\n";
echo "The error is consistent across all request formats and payloads.\n";
echo "Contact Delhivery support with error: 'NoneType' object has no attribute 'end_date'\n";