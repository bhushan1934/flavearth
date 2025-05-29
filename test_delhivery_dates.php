<?php
// Test different date formats for Delhivery API

$apiKey = '8e0534aa9d751cad7d08527493cec1f0541c7bd1';
$baseUrl = 'https://staging-express.delhivery.com';

echo "\n🗓️ TESTING DELHIVERY DATE FORMATS\n";
echo "================================\n\n";

// Different date formats to test
$dateFormats = [
    'Y-m-d',          // 2025-05-29
    'Y/m/d',          // 2025/05/29
    'd-m-Y',          // 29-05-2025
    'd/m/Y',          // 29/05/2025
    'Y-m-d H:i:s',    // 2025-05-29 17:30:00
    'Ymd',            // 20250529
];

foreach ($dateFormats as $format) {
    $date = date($format);
    echo "Testing format: $format => $date\n";
    
    $payload = [
        'shipments' => [
            [
                'name' => 'Test Customer',
                'add' => 'Test Address',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'country' => 'India',
                'phone' => '9999999999',
                'pin' => '110001',
                'order' => 'TEST-' . time(),
                'payment_mode' => 'Prepaid',
                'cod_amount' => '0',
                'products_desc' => 'Test Product',
                'hsn_code' => '0910',
                'quantity' => '1',
                'weight' => '0.5',
                'waybill' => '',
                'seller_name' => 'Flavearth',
                'seller_add' => 'Test Address',
                'seller_gst_tin' => '29AABCU9603R1ZM',
                'seller_inv' => 'INV-001',
                'seller_inv_date' => $date,
                'order_date' => $date,
                'pickup_date' => date($format, strtotime('+1 day')),
                'invoice_date' => $date
            ]
        ],
        'pickup_location' => [
            'name' => 'R K Enterprises'
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
    
    $decoded = json_decode($response, true);
    
    echo "   Status: $httpCode\n";
    if (isset($decoded['rmk'])) {
        echo "   Error: " . substr($decoded['rmk'], 0, 100) . "...\n";
    }
    if (isset($decoded['packages']) && !empty($decoded['packages'])) {
        echo "   ✅ SUCCESS! Waybill: " . $decoded['packages'][0]['waybill'] . "\n";
        break;
    }
    echo "\n";
    
    sleep(1); // Rate limiting
}