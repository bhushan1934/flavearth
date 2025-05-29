<?php
// Test different warehouse names

echo "\n🔍 TESTING WAREHOUSE NAMES\n";
echo "=========================\n\n";

$apiKey = '8e0534aa9d751cad7d08527493cec1f0541c7bd1';
$baseUrl = 'https://track.delhivery.com'; // Production

// Common warehouse name patterns to test
$warehouseNames = [
    'R K Enterprises',
    'RK Enterprises',
    'R.K. Enterprises',
    'RK ENTERPRISES',
    'Flavearth',
    'FLAVEARTH',
    'Flavearth Store',
    'FLAVEARTH STORE',
    'Test',
    'TEST',
    'Default',
    'PRIMARY'
];

echo "Testing different warehouse names in production...\n\n";

foreach ($warehouseNames as $warehouseName) {
    echo "Testing: '$warehouseName'\n";
    
    $payload = [
        'shipments' => [
            [
                'name' => 'Test Customer',
                'add' => 'Test Address',
                'pin' => '110001',
                'city' => 'New Delhi',
                'state' => 'Delhi',
                'country' => 'India',
                'phone' => '9999999999',
                'order' => 'WHTest-' . time() . '-' . rand(100, 999),
                'payment_mode' => 'Prepaid',
                'cod_amount' => '0',
                'products_desc' => 'Test',
                'quantity' => '1',
                'weight' => '0.5',
                'waybill' => '',
                'seller_name' => 'Test',
                'seller_add' => 'Test',
                'return_pin' => '110001',
                'return_city' => 'New Delhi',
                'return_phone' => '9999999999',
                'return_add' => 'Test',
                'return_state' => 'Delhi',
                'return_country' => 'India'
            ]
        ],
        'pickup_location' => [
            'name' => $warehouseName
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
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if ($data) {
        if (isset($data['packages']) && !empty($data['packages'])) {
            echo "   ✅ SUCCESS! Warehouse name is valid\n";
            echo "   Waybill: " . $data['packages'][0]['waybill'] . "\n";
            echo "\n🎉 FOUND VALID WAREHOUSE: '$warehouseName'\n";
            break;
        } else if (isset($data['rmk'])) {
            if (strpos($data['rmk'], 'ClientWarehouse') !== false) {
                echo "   ❌ Invalid warehouse name\n";
            } else {
                echo "   ⚠️  Other error: " . substr($data['rmk'], 0, 50) . "...\n";
            }
        }
    }
    
    usleep(200000); // 200ms delay between requests
}

echo "\n📌 If no valid warehouse was found, you need to:\n";
echo "1. Contact Delhivery to get your registered warehouse name\n";
echo "2. Or register a new warehouse in their system\n";
echo "3. Or check if you have a different API key for production\n";