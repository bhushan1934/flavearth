<?php
// Direct test to Delhivery staging API

echo "\n📡 DIRECT DELHIVERY STAGING API TEST\n";
echo "===================================\n\n";

$apiKey = '8e0534aa9d751cad7d08527493cec1f0541c7bd1';
$baseUrl = 'https://staging-express.delhivery.com';

// Step 1: Test authentication with a simple endpoint
echo "1. Testing API Authentication...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/c/api/pin-codes/json/?filter_codes=110001');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Token ' . $apiKey,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_VERBOSE, true);
$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

rewind($verbose);
$verboseLog = stream_get_contents($verbose);

echo "   Status: $httpCode\n";
echo "   Response: " . substr($response, 0, 100) . "\n\n";

curl_close($ch);

// Step 2: Create a minimal shipment
echo "2. Creating Minimal Shipment...\n";

$payload = [
    'shipments' => [
        [
            // Absolute minimum required fields
            'name' => 'Test Customer',
            'add' => '123 Test Street',
            'pin' => '110001',
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'country' => 'India',
            'phone' => '9999999999',
            
            // Order info
            'order' => 'TEST-' . time(),
            'payment_mode' => 'Prepaid',
            'cod_amount' => '0',
            
            // Product info
            'products_desc' => 'Test Product',
            'hsn_code' => '0910',
            'quantity' => '1',
            'commodity_value' => '100',
            'weight' => '0.5',
            
            // Seller info
            'seller_name' => 'Flavearth',
            'seller_add' => 'Test Address Delhi',
            'seller_gst_tin' => '29AABCU9603R1ZM',
            
            // Return address
            'return_pin' => '110001',
            'return_city' => 'New Delhi',
            'return_phone' => '9999999999',
            'return_add' => 'Test Return Address',
            'return_state' => 'Delhi',
            'return_country' => 'India',
            
            // Waybill
            'waybill' => ''
        ]
    ],
    'pickup_location' => [
        'name' => 'R K Enterprises'
    ]
];

echo "\nPayload being sent:\n";
echo json_encode($payload, JSON_PRETTY_PRINT) . "\n\n";

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
curl_setopt($ch, CURLOPT_VERBOSE, true);
$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

rewind($verbose);
$verboseLog = stream_get_contents($verbose);

echo "Response Status: $httpCode\n";
if ($curlError) {
    echo "CURL Error: $curlError\n";
}

$decoded = json_decode($response, true);
if ($decoded) {
    echo "\nResponse Data:\n";
    print_r($decoded);
    
    // Check if shipment was created
    if (isset($decoded['packages']) && !empty($decoded['packages'])) {
        echo "\n✅ SUCCESS! Shipment created with waybill: " . $decoded['packages'][0]['waybill'] . "\n";
    } else if (isset($decoded['rmk'])) {
        echo "\n⚠️  API Message: " . $decoded['rmk'] . "\n";
        
        // Check if it says "might be saved"
        if (strpos($decoded['rmk'], 'might be saved') !== false) {
            echo "\n📌 NOTE: The error message suggests the package MIGHT have been saved.\n";
            echo "This could mean:\n";
            echo "- The shipment was partially created but encountered an error\n";
            echo "- There's a server-side issue after initial processing\n";
            echo "- You should check with Delhivery support\n";
        }
    }
} else {
    echo "\nRaw Response:\n$response\n";
}

curl_close($ch);

// Step 3: Try to track if a waybill was mentioned
if (isset($decoded['rmk']) && preg_match('/waybill[:\s]+(\w+)/i', $decoded['rmk'], $matches)) {
    $possibleWaybill = $matches[1];
    echo "\n3. Checking if waybill $possibleWaybill exists...\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/v1/packages/json/?waybill=' . $possibleWaybill);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Token ' . $apiKey,
        'Accept: application/json'
    ]);
    
    $trackResponse = curl_exec($ch);
    $trackData = json_decode($trackResponse, true);
    
    if ($trackData && isset($trackData['ShipmentData'])) {
        echo "✅ Waybill exists in system!\n";
        print_r($trackData);
    } else {
        echo "❌ Waybill not found\n";
    }
    
    curl_close($ch);
}

echo "\n\n📝 Verbose CURL Log:\n";
echo "=====================================\n";
echo $verboseLog;