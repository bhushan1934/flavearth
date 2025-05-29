<?php
// Test Delhivery Production API

echo "\n🚀 DELHIVERY PRODUCTION API TEST\n";
echo "================================\n\n";

$apiKey = '8e0534aa9d751cad7d08527493cec1f0541c7bd1';
$baseUrl = 'https://track.delhivery.com'; // Production URL

echo "⚠️  WARNING: This will create a REAL shipment in production!\n";
echo "Base URL: $baseUrl\n\n";

// Test 1: Check API authentication
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
        echo "   ✅ Pincode 110001 is serviceable\n";
    }
} else {
    echo "   ❌ Authentication failed\n";
    echo "   Response: " . substr($response, 0, 200) . "\n";
}

echo "\n2. Creating Test Shipment in Production...\n";

$testOrder = "PROD-TEST-" . time();
$payload = [
    'shipments' => [
        [
            // Consignee details
            'name' => 'Production Test Customer',
            'add' => '123 Test Street, Connaught Place',
            'pin' => '110001',
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'country' => 'India',
            'phone' => '9999999999',
            
            // Order details
            'order' => $testOrder,
            'payment_mode' => 'Prepaid',
            'cod_amount' => '0',
            'total_amount' => '500',
            
            // Product details
            'products_desc' => 'Test Spices Package',
            'hsn_code' => '0910',
            'quantity' => '1',
            'commodity_value' => '500',
            'weight' => '0.5', // kg
            
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
            
            // Shipment details
            'shipment_width' => '20',
            'shipment_height' => '15',
            'shipment_length' => '25',
            
            // Let Delhivery generate waybill
            'waybill' => ''
        ]
    ],
    'pickup_location' => [
        'name' => 'R K Enterprises'
    ]
];

echo "Order Number: $testOrder\n\n";

// Make the request
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
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded'
]);

$startTime = microtime(true);
$response = curl_exec($ch);
$endTime = microtime(true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

$executionTime = round(($endTime - $startTime) * 1000, 2);

echo "Response Status: $httpCode\n";
echo "Execution Time: {$executionTime}ms\n";

if ($curlError) {
    echo "CURL Error: $curlError\n";
}

$responseData = json_decode($response, true);
if ($responseData) {
    echo "\nResponse Data:\n";
    print_r($responseData);
    
    // Check if shipment was created
    if (isset($responseData['packages']) && !empty($responseData['packages'])) {
        echo "\n✅ SUCCESS! Production shipment created:\n";
        foreach ($responseData['packages'] as $pkg) {
            echo "   - Waybill: " . ($pkg['waybill'] ?? 'N/A') . "\n";
            echo "   - Status: " . ($pkg['status'] ?? 'N/A') . "\n";
            echo "   - Order: " . ($pkg['order'] ?? 'N/A') . "\n";
        }
        
        // Try to track the shipment
        if (isset($responseData['packages'][0]['waybill'])) {
            $waybill = $responseData['packages'][0]['waybill'];
            echo "\n3. Tracking Created Shipment...\n";
            
            sleep(2); // Wait a bit for the system to process
            
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
                echo "   ✅ Shipment found in tracking system\n";
                echo "   Current Status: " . ($trackData['ShipmentData'][0]['Shipment']['Status']['Status'] ?? 'N/A') . "\n";
            }
        }
    } else {
        echo "\n❌ Shipment creation failed\n";
        if (isset($responseData['rmk'])) {
            echo "Message: " . $responseData['rmk'] . "\n";
        }
    }
} else {
    echo "\nRaw Response:\n$response\n";
}

echo "\n⚠️  NOTE: If successful, this created a REAL shipment in production.\n";
echo "You may need to cancel it if it was just for testing.\n";