<?php
// Test with absolute minimal payload

$apiKey = '8e0534aa9d751cad7d08527493cec1f0541c7bd1';
$baseUrl = 'https://staging-express.delhivery.com';

$minimalPayload = [
    'shipments' => [
        [
            'name' => 'Test Customer',
            'add' => 'Test Address',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'country' => 'India',
            'phone' => '9999999999',
            'pin' => '110001',
            'order' => 'TEST-001',
            'payment_mode' => 'Prepaid',
            'cod_amount' => '0',
            'products_desc' => 'Test Product',
            'quantity' => '1',
            'weight' => '0.5',
            'waybill' => ''
        ]
    ]
];

echo "\n🧪 MINIMAL DELHIVERY TEST\n";
echo "========================\n\n";

echo "Payload:\n";
echo json_encode($minimalPayload, JSON_PRETTY_PRINT) . "\n\n";

// Test with curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/cmu/create.json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($minimalPayload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Token ' . $apiKey,
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Response Status: $httpCode\n";
echo "Response:\n";
$decoded = json_decode($response, true);
if ($decoded) {
    print_r($decoded);
} else {
    echo $response;
}

// Also test form data format
echo "\n\nTesting with form data format:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/cmu/create.json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'format' => 'json',
    'data' => json_encode($minimalPayload)
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Token ' . $apiKey,
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Response Status: $httpCode\n";
echo "Response:\n";
$decoded = json_decode($response, true);
if ($decoded) {
    print_r($decoded);
} else {
    echo $response;
}