<?php
// Test Live Delhivery API with new credentials

echo "\n🔴 TESTING LIVE DELHIVERY API\n";
echo "============================\n\n";

$apiKey = '7d0e49f197932ad7899dc033ef4c2048889fcc82';
$baseUrl = 'https://track.delhivery.com'; // Production URL

echo "API Key: " . substr($apiKey, 0, 10) . "..." . substr($apiKey, -5) . "\n";
echo "Base URL: $baseUrl\n\n";

// Test 1: Authentication
echo "1. Testing API Authentication...\n";
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
if ($httpCode == 200) {
    echo "   ✅ Authentication successful\n";
    $data = json_decode($response, true);
    if (isset($data['delivery_codes']) && count($data['delivery_codes']) > 0) {
        echo "   ✅ Pincode service working\n";
    }
} else {
    echo "   ❌ Authentication failed\n";
    echo "   Response: " . substr($response, 0, 200) . "\n";
    echo "\n❌ Cannot proceed with live API test - authentication failed\n";
    exit(1);
}

// Test 2: Create a test shipment
echo "\n2. Creating Test Shipment...\n";

$testOrder = "LIVE-TEST-" . time();
$payload = [
    'shipments' => [
        [
            // Consignee details
            'name' => 'Live Test Customer',
            'add' => '123 Test Street, CP',
            'pin' => '110001',
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'country' => 'India',
            'phone' => '9999999999',
            
            // Order details
            'order' => $testOrder,
            'payment_mode' => 'Prepaid',
            'cod_amount' => '0',
            'total_amount' => '100',
            
            // Product details
            'products_desc' => 'Test Spices',
            'hsn_code' => '0910',
            'quantity' => '1',
            'commodity_value' => '100',
            'weight' => '0.5',
            
            // Seller details
            'seller_name' => 'Flavearth',
            'seller_add' => 'Flavearth Store, New Delhi',
            'seller_gst_tin' => '29AABCU9603R1ZM',
            'seller_inv' => $testOrder,
            'seller_inv_date' => date('Y-m-d'),
            
            // Return address
            'return_pin' => '110001',
            'return_city' => 'New Delhi',
            'return_phone' => '9999999999',
            'return_add' => 'Flavearth Store, New Delhi',
            'return_state' => 'Delhi',
            'return_country' => 'India',
            
            'waybill' => ''
        ]
    ],
    'pickup_location' => [
        'name' => 'flavearth store'
    ]
];

echo "Test Order: $testOrder\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/cmu/create.json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'format' => 'json',
    'data' => json_encode($payload)
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Token ' . $apiKey,
    'Accept: application/json'
]);

$startTime = microtime(true);
$response = curl_exec($ch);
$endTime = microtime(true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$executionTime = round(($endTime - $startTime) * 1000, 2);

echo "Response Status: $httpCode\n";
echo "Execution Time: {$executionTime}ms\n\n";

$responseData = json_decode($response, true);
if ($responseData) {
    echo "Response Data:\n";
    print_r($responseData);
    
    if (isset($responseData['packages']) && !empty($responseData['packages'])) {
        echo "\n🎉 SUCCESS! Live shipment created!\n";
        foreach ($responseData['packages'] as $pkg) {
            $waybill = $pkg['waybill'] ?? 'N/A';
            echo "   - Waybill: $waybill\n";
            echo "   - Status: " . ($pkg['status'] ?? 'N/A') . "\n";
            echo "   - Order: " . ($pkg['order'] ?? 'N/A') . "\n";
        }
        
        // Test tracking
        if (isset($responseData['packages'][0]['waybill'])) {
            echo "\n3. Testing Tracking...\n";
            $waybill = $responseData['packages'][0]['waybill'];
            
            sleep(2);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/v1/packages/json/?waybill=' . $waybill);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Token ' . $apiKey,
                'Accept: application/json'
            ]);
            
            $trackResponse = curl_exec($ch);
            $trackData = json_decode($trackResponse, true);
            curl_close($ch);
            
            if ($trackData && isset($trackData['ShipmentData'])) {
                echo "   ✅ Tracking successful\n";
                echo "   Status: " . ($trackData['ShipmentData'][0]['Shipment']['Status']['Status'] ?? 'N/A') . "\n";
            } else {
                echo "   ⚠️  Tracking not yet available\n";
            }
        }
        
    } else {
        echo "\n❌ Shipment creation failed\n";
        if (isset($responseData['rmk'])) {
            echo "Message: " . $responseData['rmk'] . "\n";
            
            if (strpos($responseData['rmk'], 'ClientWarehouse') !== false) {
                echo "\n📌 Warehouse Issue: Need to register 'R K Enterprises' as pickup location\n";
            }
        }
    }
} else {
    echo "Raw Response:\n$response\n";
}

echo "\n⚠️  NOTE: This uses LIVE API - any successful shipments are REAL!\n";