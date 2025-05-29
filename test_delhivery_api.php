<?php
// Run: php test_delhivery_api.php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;
use App\Models\Order;

$apiKey = config('services.delhivery.api_key');
$baseUrl = config('services.delhivery.base_url');

echo "\n🔧 DELHIVERY API TEST\n";
echo "====================\n\n";

echo "API Key: " . substr($apiKey, 0, 10) . "..." . substr($apiKey, -5) . "\n";
echo "Base URL: " . $baseUrl . "\n";
echo "Environment: " . config('services.delhivery.env') . "\n\n";

// First, test API authentication
echo "1. Testing API Authentication...\n";
$response = Http::withHeaders([
    'Authorization' => 'Token ' . $apiKey,
])->get($baseUrl . '/c/api/pin-codes/json/', [
    'filter_codes' => '110001'
]);

if ($response->successful()) {
    echo "   ✅ API Authentication successful\n\n";
} else {
    echo "   ❌ API Authentication failed\n";
    echo "   Status: " . $response->status() . "\n";
    echo "   Response: " . $response->body() . "\n";
    exit(1);
}

// Test with minimal payload
echo "2. Testing Minimal Shipment Creation...\n";

// Get a recent paid order
$order = Order::where('payment_status', 'paid')
    ->whereNotNull('shipping_address')
    ->latest()
    ->first();

if (!$order) {
    echo "   ❌ No paid order found for testing\n";
    exit(1);
}

$shippingAddress = $order->shipping_address;

// Build minimal payload based on Delhivery docs
$payload = [
    'pickup_location' => [
        'name' => config('services.delhivery.pickup_location')
    ],
    'shipments' => [
        [
            // Minimal required fields
            'name' => $shippingAddress['name'],
            'add' => $shippingAddress['address_line_1'],
            'pin' => $shippingAddress['pincode'],
            'city' => $shippingAddress['city'],
            'state' => $shippingAddress['state'],
            'country' => 'India',
            'phone' => preg_replace('/[^0-9]/', '', $shippingAddress['phone']),
            
            // Order info
            'order' => $order->order_number,
            'payment_mode' => 'Prepaid',
            'cod_amount' => '0',
            
            // Return address
            'return_pin' => '110001',
            'return_city' => 'New Delhi',
            'return_phone' => '9999999999',
            'return_add' => 'Flavearth Store',
            'return_state' => 'Delhi',
            'return_country' => 'India',
            
            // Product info
            'products_desc' => 'Spices',
            'quantity' => '1',
            'waybill' => '',
            'actual_weight' => '0.5',
            'volumetric_weight' => '0.5',
            'seller_name' => 'Flavearth'
        ]
    ]
];

echo "\nPayload:\n";
echo json_encode($payload, JSON_PRETTY_PRINT) . "\n\n";

// Try different request formats
echo "3. Testing different request formats...\n\n";

// Format 1: JSON payload
echo "   a) JSON format:\n";
$response1 = Http::withHeaders([
    'Authorization' => 'Token ' . $apiKey,
    'Content-Type' => 'application/json'
])->post($baseUrl . '/api/cmu/create.json', $payload);

echo "      Status: " . $response1->status() . "\n";
echo "      Response: " . substr($response1->body(), 0, 200) . "\n\n";

// Format 2: Form data with JSON string
echo "   b) Form data format:\n";
$response2 = Http::asForm()->withHeaders([
    'Authorization' => 'Token ' . $apiKey,
])->post($baseUrl . '/api/cmu/create.json', [
    'format' => 'json',
    'data' => json_encode($payload)
]);

echo "      Status: " . $response2->status() . "\n";
echo "      Response: " . substr($response2->body(), 0, 200) . "\n\n";

// Format 3: Direct shipments array
echo "   c) Direct shipments format:\n";
$response3 = Http::withHeaders([
    'Authorization' => 'Token ' . $apiKey,
    'Content-Type' => 'application/json'
])->post($baseUrl . '/api/cmu/create.json', [
    'format' => 'json',
    'data' => json_encode(['shipments' => $payload['shipments']])
]);

echo "      Status: " . $response3->status() . "\n";
echo "      Response: " . substr($response3->body(), 0, 200) . "\n\n";

// Check if any response has more details
if (!$response2->successful()) {
    echo "\n📋 Full error response:\n";
    echo $response2->body() . "\n";
}