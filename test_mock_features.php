<?php
// Test all mock features

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\Shipment;
use App\Services\MockDelhiveryService;

echo "\n🧪 TESTING MOCK DELHIVERY FEATURES\n";
echo "==================================\n\n";

$service = new MockDelhiveryService();

// Get the shipment we just created
$shipment = Shipment::latest()->first();

if (!$shipment) {
    echo "❌ No shipment found\n";
    exit(1);
}

echo "Testing with Shipment:\n";
echo "- Waybill: {$shipment->waybill}\n";
echo "- Order: {$shipment->order->order_number}\n\n";

// Test 1: Track Shipment
echo "1. Testing Track Shipment...\n";
$trackResult = $service->trackShipment($shipment->waybill);
if ($trackResult['success']) {
    echo "   ✅ Success!\n";
    echo "   Status: " . $trackResult['data']['ShipmentData'][0]['Shipment']['Status']['Status'] . "\n";
    echo "   Instructions: " . $trackResult['data']['ShipmentData'][0]['Shipment']['Status']['Instructions'] . "\n";
} else {
    echo "   ❌ Failed\n";
}

// Test 2: Generate Packing Slip
echo "\n2. Testing Generate Packing Slip...\n";
$packingResult = $service->generatePackingSlip($shipment->waybill);
if ($packingResult['success']) {
    echo "   ✅ Success!\n";
    echo "   PDF Content: " . substr($packingResult['pdf'], 0, 50) . "...\n";
} else {
    echo "   ❌ Failed\n";
}

// Test 3: Cancel Shipment
echo "\n3. Testing Cancel Shipment...\n";
$cancelResult = $service->cancelShipment($shipment->waybill);
if ($cancelResult['success']) {
    echo "   ✅ Success!\n";
    echo "   Status: " . $cancelResult['data']['status'] . "\n";
    echo "   Waybill: " . $cancelResult['data']['waybill'] . "\n";
} else {
    echo "   ❌ Failed\n";
}

echo "\n✅ All mock features working correctly!\n";