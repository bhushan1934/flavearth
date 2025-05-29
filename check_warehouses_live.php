<?php
// Check warehouses for live API key

echo "\n🏪 CHECKING LIVE API WAREHOUSES\n";
echo "==============================\n\n";

$apiKey = '7d0e49f197932ad7899dc033ef4c2048889fcc82';
$baseUrl = 'https://track.delhivery.com';

// Try different warehouse endpoints
$endpoints = [
    '/api/backend/clientwarehouse/json/',
    '/api/frontend/warehouses/',
    '/api/v1/warehouses/',
    '/c/api/warehouses/',
    '/api/warehouses/',
    '/pickuplocations/'
];

foreach ($endpoints as $endpoint) {
    echo "Testing endpoint: $endpoint\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . $endpoint);
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
        if ($data) {
            echo "   ✅ Success! Found data:\n";
            if (is_array($data) && isset($data['data'])) {
                echo "   Warehouses found: " . count($data['data']) . "\n";
                foreach ($data['data'] as $warehouse) {
                    echo "   - " . ($warehouse['name'] ?? $warehouse['registered_name'] ?? 'Unnamed') . "\n";
                }
            } else {
                echo "   Response structure:\n";
                echo "   " . json_encode(array_keys($data), JSON_PRETTY_PRINT) . "\n";
            }
        }
    } else {
        echo "   ❌ " . substr($response, 0, 100) . "\n";
    }
    echo "\n";
}

// Try creating a warehouse with common names
echo "Testing common warehouse patterns...\n\n";

$commonNames = [
    'Main',
    'Primary', 
    'Default',
    'Store',
    'Warehouse',
    'Hub',
    'DC',
    'Fulfillment Center'
];

foreach ($commonNames as $name) {
    echo "Testing '$name'...\n";
    
    $payload = [
        'shipments' => [
            [
                'name' => 'Test',
                'add' => 'Test',
                'pin' => '110001',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'country' => 'India',
                'phone' => '9999999999',
                'order' => 'TEST-' . time() . '-' . rand(100, 999),
                'payment_mode' => 'Prepaid',
                'cod_amount' => '0',
                'products_desc' => 'Test',
                'quantity' => '1',
                'weight' => '0.5',
                'waybill' => ''
            ]
        ],
        'pickup_location' => [
            'name' => $name
        ]
    ];
    
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
    
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    
    if ($data && isset($data['packages']) && !empty($data['packages'])) {
        echo "   🎉 SUCCESS! Valid warehouse: '$name'\n";
        echo "   Waybill: " . $data['packages'][0]['waybill'] . "\n";
        break;
    } else if ($data && isset($data['rmk'])) {
        if (strpos($data['rmk'], 'ClientWarehouse') === false) {
            echo "   ⚠️  Different error: " . substr($data['rmk'], 0, 50) . "...\n";
        } else {
            echo "   ❌ Not found\n";
        }
    }
    
    usleep(200000); // 200ms delay
}

echo "\n📋 Summary:\n";
echo "If no warehouses were found, contact Delhivery support to:\n";
echo "1. Register a pickup location/warehouse\n";
echo "2. Get the exact registered name\n";
echo "3. Confirm API key permissions\n";