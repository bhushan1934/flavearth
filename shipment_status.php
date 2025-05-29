<?php
// Run: php shipment_status.php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\Shipment;

echo "\n🚚 SHIPMENT STATUS DASHBOARD\n";
echo "============================\n\n";

// Summary
$totalOrders = Order::count();
$ordersWithShipments = Order::whereHas('shipment')->count();
$totalShipments = Shipment::count();

echo "📊 Summary:\n";
echo "- Total Orders: $totalOrders\n";
echo "- Orders with Shipments: $ordersWithShipments\n";
echo "- Total Shipments: $totalShipments\n";
echo "- Auto-Create Enabled: " . (config('services.delhivery.auto_create_shipment') ? '✅ Yes' : '❌ No') . "\n\n";

// Recent Orders
echo "📦 Last 5 Orders:\n";
echo str_repeat("-", 80) . "\n";
$orders = Order::with('shipment')->latest()->take(5)->get();

foreach ($orders as $order) {
    echo "Order #{$order->order_number}\n";
    echo "  Status: {$order->status} | Payment: {$order->payment_status}\n";
    
    if ($order->shipment) {
        echo "  ✅ Shipment Created\n";
        echo "  Waybill: {$order->shipment->waybill}\n";
        echo "  Status: {$order->shipment->status}\n";
    } else {
        echo "  ❌ No Shipment\n";
    }
    echo "\n";
}

// Check recent logs
echo "📝 Recent Delhivery Logs:\n";
echo str_repeat("-", 80) . "\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logs = shell_exec("tail -100 $logFile | grep -i 'delhivery\\|shipment' | tail -5");
    echo $logs ?: "No recent Delhivery logs found.\n";
}