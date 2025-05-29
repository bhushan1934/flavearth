<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🛒 TESTING ORDER & DELIVERY INTEGRATION\n";
echo "=====================================\n\n";

// Get or create test user
$user = App\Models\User::first();
if (!$user) {
    echo "Creating test user...\n";
    $user = App\Models\User::factory()->create(['email' => 'test@example.com']);
}
echo "User: {$user->email}\n";

// Get test product
$product = App\Models\Product::first();
if (!$product) {
    echo "❌ No products found.\n";
    exit;
}
echo "Product: {$product->name} - ₹{$product->price}\n";

// Create test cart and order
$cart = $user->cart ?: $user->cart()->create();
$cartItem = $cart->items()->create([
    'product_id' => $product->id,
    'quantity' => 1,
    'price' => $product->price
]);

$address = [
    'name' => 'Test Customer',
    'phone' => '9999999999',
    'address_line_1' => '123 Test Street',
    'city' => 'New Delhi',
    'state' => 'Delhi',
    'pincode' => '110001',
    'country' => 'India'
];

$subtotal = $product->price;
$gst = $subtotal * 0.18;
$total = $subtotal + $gst;

$order = App\Models\Order::create([
    'user_id' => $user->id,
    'order_number' => 'TEST-' . time(),
    'subtotal' => $subtotal,
    'gst' => $gst,
    'total' => $total,
    'shipping_address' => json_encode($address),
    'status' => 'pending',
    'payment_status' => 'paid'
]);

$order->items()->create([
    'product_id' => $product->id,
    'quantity' => 1,
    'price' => $product->price,
    'total' => $product->price
]);

echo "\n📦 Order Created: {$order->order_number}\n";
echo "Total: ₹{$order->total}\n\n";

// Test shipment creation
echo "🚚 Testing Delivery Integration...\n";

$useMock = config('services.delhivery.use_mock', false);
echo "Service Mode: " . ($useMock ? "Mock" : "Live API") . "\n";

if ($useMock) {
    $service = new App\Services\MockDelhiveryService();
} else {
    $service = new App\Services\DelhiveryService();
}

$result = $service->createShipment(['order' => $order]);

if ($result['success']) {
    echo "✅ Shipment created successfully!\n";
    $waybill = $result['data']['packages'][0]['waybill'];
    echo "📋 Waybill: {$waybill}\n";
    
    // Create shipment record
    $shipment = App\Models\Shipment::create([
        'order_id' => $order->id,
        'waybill' => $waybill,
        'carrier' => 'delhivery',
        'status' => 'created'
    ]);
    
    echo "💾 Shipment record saved\n";
    
    // Test tracking
    echo "\n📍 Testing Tracking...\n";
    $trackResult = $service->trackShipment($waybill);
    
    if ($trackResult['success']) {
        echo "✅ Tracking successful\n";
        if (isset($trackResult['data']['ShipmentData'][0]['Shipment']['Status'])) {
            $status = $trackResult['data']['ShipmentData'][0]['Shipment']['Status']['Status'];
            echo "📦 Status: {$status}\n";
        }
    } else {
        echo "❌ Tracking failed\n";
    }
    
} else {
    echo "❌ Shipment creation failed\n";
    echo "Error: {$result['error']}\n";
    
    if (isset($result['needs_support']) && $result['needs_support']) {
        echo "\n⚠️  This requires Delhivery support intervention\n";
    }
}

echo "\n📊 SUMMARY:\n";
echo "Order: {$order->order_number}\n";
echo "Status: {$order->status}\n";
echo "Payment: {$order->payment_status}\n";
echo "Delivery Service: " . ($useMock ? "Mock (Testing)" : "Live API") . "\n";

if ($order->shipment) {
    echo "Shipment: {$order->shipment->waybill}\n";
    echo "Tracking Status: {$order->shipment->status}\n";
} else {
    echo "Shipment: Not created\n";
}