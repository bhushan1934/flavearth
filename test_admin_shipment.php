<?php
// Test creating shipment through admin controller

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Http\Controllers\Admin\AdminOrderController;
use Illuminate\Http\Request;

echo "\n🎛️ TESTING ADMIN SHIPMENT CREATION\n";
echo "==================================\n\n";

// Get a recent paid order without shipment
$order = Order::where('payment_status', 'paid')
    ->whereDoesntHave('shipment')
    ->latest()
    ->first();

if (!$order) {
    echo "❌ No suitable order found for testing\n";
    exit(1);
}

echo "Order Details:\n";
echo "- Order Number: {$order->order_number}\n";
echo "- Order ID: {$order->id}\n";
echo "- Total: ₹{$order->total}\n";
echo "- Status: {$order->status}\n\n";

// Create controller instance
$controller = new AdminOrderController();

// Create mock request
$request = new Request();

echo "Creating shipment through admin controller...\n";

try {
    $response = $controller->createShipment($request, $order);
    $data = json_decode($response->getContent(), true);
    
    echo "\nResponse:\n";
    echo "- Success: " . ($data['success'] ? '✅ Yes' : '❌ No') . "\n";
    echo "- Message: " . ($data['message'] ?? 'N/A') . "\n";
    
    if (isset($data['waybill'])) {
        echo "- Waybill: " . $data['waybill'] . "\n";
        echo "- Shipment ID: " . $data['shipment_id'] . "\n";
    }
    
    if (isset($data['error'])) {
        echo "- Error: " . $data['error'] . "\n";
    }
    
    // Check if shipment was created
    $order->refresh();
    if ($order->shipment) {
        echo "\n✅ Shipment successfully created in database!\n";
        echo "- Waybill: {$order->shipment->waybill}\n";
        echo "- Status: {$order->shipment->status}\n";
        echo "- Courier: {$order->shipment->courier_name}\n";
    } else {
        echo "\n❌ No shipment found in database\n";
    }
    
} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
}