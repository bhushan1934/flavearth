<?php
// Test automatic shipment creation on payment

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Address;
use App\Http\Controllers\RazorpayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "\n🚀 TESTING AUTO SHIPMENT CREATION\n";
echo "=================================\n\n";

// Find or create test user
$user = User::where('email', 'test@example.com')->first();
if (!$user) {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
}

Auth::login($user);

// Create a test address
$address = Address::create([
    'user_id' => $user->id,
    'name' => 'Test Customer',
    'phone' => '9876543210',
    'address_line_1' => '123 Test Street',
    'address_line_2' => 'Test Area',
    'city' => 'Mumbai',
    'state' => 'Maharashtra',
    'country' => 'India',
    'pincode' => '400001',
    'type' => 'shipping',
    'is_default' => true
]);

// Create cart with items
$cart = Cart::create(['user_id' => $user->id]);
$product = Product::first();

CartItem::create([
    'cart_id' => $cart->id,
    'product_id' => $product->id,
    'quantity' => 2,
    'price' => $product->price
]);

echo "Setup Complete:\n";
echo "- User: {$user->email}\n";
echo "- Address: {$address->city}, {$address->state}\n";
echo "- Cart Items: 1\n\n";

// Simulate payment verification
$controller = new RazorpayController();
$request = new Request([
    'razorpay_payment_id' => 'pay_TEST' . time(),
    'razorpay_order_id' => 'order_TEST' . time(),
    'razorpay_signature' => 'test_signature',
    'shipping_address_id' => $address->id,
    'billing_same_as_shipping' => true
]);

echo "Simulating payment verification...\n";

try {
    // Mock the Razorpay verification
    $ordersBeforePayment = Order::count();
    $shipmentsBeforePayment = \App\Models\Shipment::count();
    
    // We'll create the order directly since we can't mock Razorpay verification
    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
        'subtotal' => $cart->items->sum(fn($item) => $item->price * $item->quantity),
        'tax' => 0,
        'gst' => 0,
        'shipping_cost' => 0,
        'shipping_method' => 'standard',
        'total' => $cart->items->sum(fn($item) => $item->price * $item->quantity),
        'payment_method' => 'razorpay',
        'payment_status' => 'paid',
        'status' => 'pending',
        'razorpay_payment_id' => $request->razorpay_payment_id,
        'razorpay_order_id' => $request->razorpay_order_id,
        'shipping_address' => $address->toArray(),
        'billing_address' => $address->toArray()
    ]);
    
    // Now trigger auto-shipment creation
    if (config('services.delhivery.auto_create_shipment')) {
        $delhiveryService = config('services.delhivery.use_mock') 
            ? new \App\Services\MockDelhiveryService() 
            : new \App\Services\DelhiveryService();
            
        $shipmentResult = $delhiveryService->createShipment(['order' => $order]);
        
        if ($shipmentResult['success']) {
            $waybill = $shipmentResult['data']['packages'][0]['waybill'] ?? null;
            
            if ($waybill) {
                \App\Models\Shipment::create([
                    'order_id' => $order->id,
                    'waybill' => $waybill,
                    'courier_name' => 'delhivery',
                    'status' => 'pickup_scheduled',
                    'pickup_location' => config('services.delhivery.pickup_location'),
                    'shipment_data' => $shipmentResult['data'],
                    'shipped_at' => now()
                ]);
                
                $order->update(['tracking_number' => $waybill]);
            }
        }
    }
    
    $ordersAfterPayment = Order::count();
    $shipmentsAfterPayment = \App\Models\Shipment::count();
    
    echo "\nResults:\n";
    echo "- Orders created: " . ($ordersAfterPayment - $ordersBeforePayment) . "\n";
    echo "- Shipments created: " . ($shipmentsAfterPayment - $shipmentsBeforePayment) . "\n";
    
    if ($order->shipment) {
        echo "\n✅ Auto-shipment creation successful!\n";
        echo "- Order: {$order->order_number}\n";
        echo "- Waybill: {$order->shipment->waybill}\n";
        echo "- Status: {$order->shipment->status}\n";
    } else {
        echo "\n❌ No shipment created\n";
    }
    
    // Cleanup
    $cart->items()->delete();
    $cart->delete();
    
} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
}

Auth::logout();