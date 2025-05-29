<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart;
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }
        
        $cartItems = $cart->items()->with(['product', 'variant'])->get();
        $addresses = $user->addresses;
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item->variant ? $item->variant->price : $item->product->price;
            $subtotal += $price * $item->quantity;
        }
        
        $gst = $subtotal * 0.18;
        $total = $subtotal + $gst;
        
        return view('checkout.index', compact('cartItems', 'addresses', 'subtotal', 'gst', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_method' => 'required|in:standard,express',
            'address_id' => 'required_without:new_address',
            'new_address.name' => 'required_with:new_address',
            'new_address.phone' => 'required_with:new_address',
            'new_address.address_line_1' => 'required_with:new_address',
            'new_address.city' => 'required_with:new_address',
            'new_address.state' => 'required_with:new_address',
            'new_address.pincode' => 'required_with:new_address',
        ]);
        
        // Store address data in session for Razorpay payment
        session([
            'checkout_data' => $request->all()
        ]);
        
        return response()->json(['success' => true]);
    }

    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        $cart = $user->cart;
        
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty']);
        }
        
        $request->validate([
            'shipping_method' => 'required|in:standard,express',
            'address_id' => 'required_without:new_address',
            'new_address.name' => 'required_with:new_address',
            'new_address.phone' => 'required_with:new_address',
            'new_address.address_line_1' => 'required_with:new_address',
            'new_address.city' => 'required_with:new_address',
            'new_address.state' => 'required_with:new_address',
            'new_address.pincode' => 'required_with:new_address',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Handle address
            if ($request->has('new_address')) {
                $addressData = $request->new_address;
                $addressData['user_id'] = $user->id;
                $addressData['type'] = 'shipping';
                $addressData['country'] = $addressData['country'] ?? 'India';
                
                // Get checkbox values from request
                $saveAddress = $request->input('new_address.save_address', false);
                $isDefault = $request->input('new_address.is_default', false);
                
                // If save_address is true, create the address
                if ($saveAddress) {
                    // If setting as default, update other addresses
                    if ($isDefault) {
                        $user->addresses()->update(['is_default' => false]);
                        $addressData['is_default'] = true;
                    }
                    
                    $address = Address::create($addressData);
                } else {
                    // Create temporary address for this order
                    $address = new Address($addressData);
                }
            } else {
                $address = Address::findOrFail($request->address_id);
                if ($address->user_id !== $user->id) {
                    throw new \Exception('Invalid address');
                }
            }
            
            // Calculate order totals
            $cartItems = $cart->items()->with(['product', 'variant'])->get();
            $subtotal = 0;
            
            foreach ($cartItems as $item) {
                $price = $item->variant ? $item->variant->price : $item->product->price;
                $subtotal += $price * $item->quantity;
            }
            
            $gst = $subtotal * 0.18;
            $shipping_cost = $request->shipping_method === 'express' ? 100 : 0;
            $total = $subtotal + $gst + $shipping_cost;
            
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
                'subtotal' => $subtotal,
                'gst' => $gst,
                'shipping_cost' => $shipping_cost,
                'total' => $total,
                'shipping_method' => $request->shipping_method,
                'shipping_address' => json_encode([
                    'name' => $address->name,
                    'phone' => $address->phone,
                    'address_line_1' => $address->address_line_1,
                    'address_line_2' => $address->address_line_2,
                    'city' => $address->city,
                    'state' => $address->state,
                    'country' => $address->country,
                    'pincode' => $address->pincode,
                ]),
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);
            
            // Create order items
            foreach ($cartItems as $item) {
                $price = $item->variant ? $item->variant->price : $item->product->price;
                
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'total' => $price * $item->quantity
                ]);
            }
            
            // Clear cart
            $cart->items()->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'order_id' => $order->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Order placement failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.',
                'error' => $e->getMessage() // Only for debugging, remove in production
            ]);
        }
    }

    public function orderConfirmation($orderId)
    {
        $order = Order::with(['items.product', 'items.variant', 'shipment'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);
            
        return view('checkout.confirmation', compact('order'));
    }
}