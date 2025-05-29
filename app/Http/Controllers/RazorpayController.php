<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Shipment;
use App\Services\DelhiveryService;
use App\Services\MockDelhiveryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RazorpayController extends Controller
{
    private $razorpayKey;
    private $razorpaySecret;

    public function __construct()
    {
        $this->razorpayKey = config('services.razorpay.key');
        $this->razorpaySecret = config('services.razorpay.secret');
    }

    public function createOrder(Request $request)
    {
        try {
            $api = new Api($this->razorpayKey, $this->razorpaySecret);
            
            $cart = Cart::where('user_id', Auth::id())->first();
            if (!$cart || $cart->items->isEmpty()) {
                return response()->json(['error' => 'Cart is empty'], 400);
            }

            $amount = $cart->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $razorpayOrder = $api->order->create([
                'amount' => $amount * 100,
                'currency' => 'INR',
                'receipt' => 'order_' . time(),
                'payment_capture' => 1
            ]);

            return response()->json([
                'order_id' => $razorpayOrder->id,
                'amount' => $amount,
                'currency' => 'INR',
                'key' => $this->razorpayKey
            ]);

        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create order'], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        Log::info('Payment verification started', $request->all());
        
        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
        ]);

        try {
            $api = new Api($this->razorpayKey, $this->razorpaySecret);

            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            Log::info('Verifying payment signature', $attributes);
            $api->utility->verifyPaymentSignature($attributes);
            Log::info('Payment signature verified successfully');

            DB::beginTransaction();

            $cart = Cart::where('user_id', Auth::id())->first();
            $checkoutData = session('checkout_data');
            
            if (!$checkoutData) {
                throw new \Exception('Checkout data not found');
            }
            
            // Handle address
            $user = Auth::user();
            if (isset($checkoutData['new_address'])) {
                $addressData = $checkoutData['new_address'];
                $addressData['user_id'] = $user->id;
                $addressData['type'] = 'shipping';
                $addressData['country'] = $addressData['country'] ?? 'India';
                
                $saveAddress = $addressData['save_address'] ?? false;
                $isDefault = $addressData['is_default'] ?? false;
                
                if ($saveAddress) {
                    if ($isDefault) {
                        $user->addresses()->update(['is_default' => false]);
                        $addressData['is_default'] = true;
                    }
                    $address = Address::create($addressData);
                } else {
                    $address = new Address($addressData);
                }
            } else {
                $address = Address::findOrFail($checkoutData['address_id']);
            }
            
            // Calculate totals
            $subtotal = $cart->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            $gst = $subtotal * 0.18;
            $shipping_cost = $checkoutData['shipping_method'] === 'express' ? 100 : 0;
            $total = $subtotal + $gst + $shipping_cost;
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
                'subtotal' => $subtotal,
                'gst' => $gst,
                'shipping_cost' => $shipping_cost,
                'total' => $total,
                'total_amount' => $total,
                'shipping_method' => $checkoutData['shipping_method'],
                'payment_status' => 'paid',
                'payment_method' => 'razorpay',
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_order_id' => $request->razorpay_order_id,
                'status' => 'pending',
                'shipping_address' => [
                    'name' => $address->name,
                    'phone' => $address->phone,
                    'address_line_1' => $address->address_line_1,
                    'address_line_2' => $address->address_line_2,
                    'city' => $address->city,
                    'state' => $address->state,
                    'country' => $address->country,
                    'pincode' => $address->pincode,
                ],
                'billing_address' => [
                    'name' => $address->name,
                    'phone' => $address->phone,
                    'address_line_1' => $address->address_line_1,
                    'address_line_2' => $address->address_line_2,
                    'city' => $address->city,
                    'state' => $address->state,
                    'country' => $address->country,
                    'pincode' => $address->pincode,
                ]
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity
                ]);

                if ($item->variant) {
                    $item->variant->decrement('stock_quantity', $item->quantity);
                } else {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }

            $cart->items()->delete();

            DB::commit();

            // Auto-create Delhivery shipment if enabled
            if (config('services.delhivery.auto_create_shipment')) {
                try {
                    $delhiveryService = config('services.delhivery.use_mock') 
                        ? new MockDelhiveryService() 
                        : new DelhiveryService();
                    $shipmentResult = $delhiveryService->createShipment(['order' => $order]);
                    
                    if ($shipmentResult['success']) {
                        $responseData = $shipmentResult['data'];
                        $waybill = $responseData['packages'][0]['waybill'] ?? null;
                        
                        if ($waybill) {
                            Shipment::create([
                                'order_id' => $order->id,
                                'waybill' => $waybill,
                                'courier_name' => 'delhivery',
                                'status' => 'pickup_scheduled',
                                'pickup_location' => config('services.delhivery.pickup_location'),
                                'shipment_data' => $responseData,
                                'shipped_at' => now()
                            ]);

                            $order->update(['tracking_number' => $waybill]);
                            
                            Log::info('Auto-created Delhivery shipment', [
                                'order_id' => $order->id,
                                'waybill' => $waybill
                            ]);
                        }
                    } else {
                        Log::error('Failed to auto-create Delhivery shipment', [
                            'order_id' => $order->id,
                            'error' => $shipmentResult['error'] ?? 'Unknown error'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Exception while auto-creating shipment', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            session()->forget(['checkout_data']);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'redirect_url' => route('checkout.confirmation', ['orderId' => $order->id])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment verification failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'error' => 'Payment verification failed',
                'message' => $e->getMessage() // For debugging
            ], 400);
        }
    }
}