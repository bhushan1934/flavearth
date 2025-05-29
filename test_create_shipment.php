<?php
// Run: php test_create_shipment.php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Services\DelhiveryService;
use App\Services\MockDelhiveryService;

echo "\n📦 TESTING DELHIVERY SHIPMENT CREATION\n";
echo "=====================================\n\n";

// Get a recent paid order without shipment
$order = Order::where('payment_status', 'paid')
    ->whereDoesntHave('shipment')
    ->whereNotNull('shipping_address')
    ->latest()
    ->first();

if (!$order) {
    echo "❌ No suitable order found for testing\n";
    exit(1);
}

echo "Order Details:\n";
echo "- Order Number: {$order->order_number}\n";
echo "- Total Amount: ₹{$order->total}\n";
echo "- Payment Status: {$order->payment_status}\n";
echo "- Current Status: {$order->status}\n\n";

$shippingAddress = $order->shipping_address;
echo "Shipping Address:\n";
echo "- Name: {$shippingAddress['name']}\n";
echo "- Address: {$shippingAddress['address_line_1']}\n";
echo "- City: {$shippingAddress['city']}\n";
echo "- Pincode: {$shippingAddress['pincode']}\n\n";

// Test API connection first
echo "1. Testing API Connection...\n";
$service = config('services.delhivery.use_mock') 
    ? new MockDelhiveryService() 
    : new DelhiveryService();
    
if (config('services.delhivery.use_mock')) {
    echo "   ⚠️  Using Mock Service (API issues detected)\n\n";
} else {
    echo "   Using Live API\n\n";
}
$connectionTest = $service->testConnection();

if ($connectionTest['success']) {
    echo "   ✅ API connection successful (Method: {$connectionTest['auth_method']})\n\n";
} else {
    echo "   ❌ API connection failed\n";
    echo "   Status: " . ($connectionTest['status'] ?? 'Unknown') . "\n";
    echo "   Response: " . substr($connectionTest['response'] ?? '', 0, 200) . "\n\n";
    
    // Continue anyway to see the exact error
}

// Try to create shipment
echo "2. Creating Shipment...\n";
$result = $service->createShipment(['order' => $order]);

if ($result['success']) {
    echo "   ✅ Shipment created successfully!\n";
    
    if (isset($result['data']['packages'][0]['waybill'])) {
        echo "   Waybill: " . $result['data']['packages'][0]['waybill'] . "\n";
    }
    
    echo "\n   Full Response:\n";
    print_r($result['data']);
} else {
    echo "   ❌ Shipment creation failed\n";
    echo "   Error: " . ($result['error'] ?? 'Unknown error') . "\n";
    
    if (isset($result['response'])) {
        echo "\n   Full Response:\n";
        print_r($result['response']);
    }
}

echo "\n\n";

// Check logs
echo "3. Recent Error Logs:\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logs = shell_exec("tail -20 $logFile | grep -i 'delhivery\\|shipment' | tail -10");
    echo $logs ?: "No recent Delhivery logs found.\n";
}