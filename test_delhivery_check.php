<?php
// Check if shipments are being created despite the error

echo "\n🔍 CHECKING DELHIVERY SHIPMENT STATUS\n";
echo "====================================\n\n";

$apiKey = '8e0534aa9d751cad7d08527493cec1f0541c7bd1';
$baseUrl = 'https://staging-express.delhivery.com';

// List of test order numbers we've tried
$testOrders = [
    'TEST-1748542627',
    'TEST-' . (time() - 60),
    'TEST-' . (time() - 120),
    'ORD-1748537952-3743'
];

echo "Checking if any test orders exist in Delhivery system...\n\n";

foreach ($testOrders as $orderNo) {
    echo "Checking order: $orderNo\n";
    
    // Try to search by reference number
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/v1/packages/json/?ref_no=' . urlencode($orderNo));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Token ' . $apiKey,
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "   Status: $httpCode\n";
    
    if ($httpCode == 200) {
        $data = json_decode($response, true);
        if ($data && isset($data['ShipmentData']) && !empty($data['ShipmentData'])) {
            echo "   ✅ FOUND! Shipment exists\n";
            print_r($data);
        } else {
            echo "   ❌ Not found\n";
        }
    } else {
        echo "   ❌ Error response\n";
    }
    echo "\n";
}

// Try alternative endpoints
echo "\n2. Testing alternative endpoints...\n";

// Try the waybill generation endpoint
echo "\nTrying waybill generation endpoint...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/waybill/api/bulk/json/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['count' => 1]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Token ' . $apiKey,
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
if ($httpCode == 200) {
    $data = json_decode($response, true);
    echo "Response:\n";
    print_r($data);
} else {
    echo "Error: " . substr($response, 0, 100) . "\n";
}

// Check pickup locations
echo "\n3. Checking registered pickup locations...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/backend/clientwarehouse/json/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Token ' . $apiKey,
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
if ($httpCode == 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['data'])) {
        echo "\nRegistered pickup locations:\n";
        foreach ($data['data'] as $location) {
            echo "- Name: " . ($location['name'] ?? 'N/A') . "\n";
            echo "  Pin: " . ($location['pin'] ?? 'N/A') . "\n";
            echo "  Registered: " . ($location['registered_name'] ?? 'N/A') . "\n\n";
        }
    } else {
        echo "Response:\n";
        print_r($data);
    }
} else {
    echo "Error: " . substr($response, 0, 200) . "\n";
}