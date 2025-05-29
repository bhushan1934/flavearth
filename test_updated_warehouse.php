<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🏪 TESTING UPDATED WAREHOUSE CONFIGURATION\n";
echo "=========================================\n\n";

// Display current configuration
echo "📋 Current Configuration:\n";
echo "API Key: " . substr(config('services.delhivery.api_key'), 0, 10) . "...\n";
echo "Environment: " . config('services.delhivery.env') . "\n";
echo "Base URL: " . config('services.delhivery.base_url') . "\n";
echo "Pickup Location: " . config('services.delhivery.pickup_location') . "\n";
echo "Use Mock: " . (config('services.delhivery.use_mock') ? 'Yes' : 'No') . "\n\n";

// Test API connection
echo "🔐 Testing API Authentication...\n";
$service = new App\Services\DelhiveryService();
$connectionTest = $service->testConnection();

if ($connectionTest['success']) {
    echo "✅ Authentication successful\n\n";
} else {
    echo "❌ Authentication failed: " . $connectionTest['message'] . "\n\n";
    exit;
}

// Test warehouse with shipment creation
echo "📦 Testing Shipment Creation with Updated Warehouse...\n";

// Create test order data
$testOrderData = [
    'order' => (object) [
        'id' => 999,
        'order_number' => 'WAREHOUSE-TEST-' . time(),
        'total' => 500,
        'subtotal' => 424,
        'payment_status' => 'paid',
        'shipping_address' => json_encode([
            'name' => 'Warehouse Test Customer',
            'phone' => '9999999999',
            'address_line_1' => '123 Test Street',
            'address_line_2' => 'Near Connaught Place',
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'pincode' => '110001',
            'country' => 'India'
        ]),
        'items' => collect([
            (object) ['quantity' => 2]
        ]),
        'created_at' => now()
    ]
];

echo "Test Order: " . $testOrderData['order']->order_number . "\n";
echo "Pickup Location: " . config('services.delhivery.pickup_location') . "\n\n";

$result = $service->createShipment($testOrderData);

if ($result['success']) {
    echo "✅ SUCCESS! Live shipment created with updated warehouse:\n";
    $package = $result['data']['packages'][0] ?? [];
    echo "📋 Waybill: " . ($package['waybill'] ?? 'N/A') . "\n";
    echo "📦 Status: " . ($package['status'] ?? 'N/A') . "\n";
    echo "🏪 Warehouse: " . config('services.delhivery.pickup_location') . "\n\n";
    
    // Test tracking immediately
    if (isset($package['waybill'])) {
        $waybill = $package['waybill'];
        echo "📍 Testing Tracking for Waybill: $waybill\n";
        
        $trackResult = $service->trackShipment($waybill);
        if ($trackResult['success']) {
            echo "✅ Tracking successful\n";
            if (isset($trackResult['data']['ShipmentData'][0]['Shipment']['Status'])) {
                $status = $trackResult['data']['ShipmentData'][0]['Shipment']['Status']['Status'];
                echo "📦 Current Status: $status\n";
            }
        } else {
            echo "❌ Tracking failed\n";
        }
    }
    
} else {
    echo "❌ Shipment creation failed\n";
    echo "Error: " . $result['error'] . "\n";
    
    if (isset($result['response']['rmk'])) {
        echo "API Message: " . $result['response']['rmk'] . "\n";
    }
    
    // Check if it's still a warehouse issue
    if (strpos($result['error'], 'ClientWarehouse') !== false || 
        strpos($result['error'], 'warehouse') !== false) {
        echo "\n⚠️  Still a warehouse registration issue.\n";
        echo "The warehouse 'flavearth store' may not be registered yet.\n";
        echo "Contact Delhivery support to verify warehouse registration.\n";
    }
}

echo "\n📊 Summary:\n";
echo "Warehouse Name: " . config('services.delhivery.pickup_location') . "\n";
echo "API Environment: " . config('services.delhivery.env') . "\n";
echo "Integration Status: " . ($result['success'] ? "✅ Working" : "❌ Failed") . "\n";