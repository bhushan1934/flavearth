<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart ? $cart->items()->with(['product', 'variant'])->get() : collect();
        $total = $cart ? $cart->total_amount : 0;

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = ProductVariant::findOrFail($request->variant_id);
        
        // Validate that variant belongs to product
        if ($variant->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid product variant'
            ], 400);
        }
        
        // Check if variant is available and in stock
        if (!$variant->is_available || $variant->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Product variant is out of stock or insufficient quantity available'
            ], 400);
        }

        $cart = $this->getOrCreateCart();
        
        // Check if item already exists in cart (same product and variant)
        $cartItem = $cart->items()
            ->where('product_id', $product->id)
            ->where('variant_id', $variant->id)
            ->first();
        
        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if ($newQuantity > $variant->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add more items. Stock limit reached.'
                ], 400);
            }
            
            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $variant->price
            ]);
        } else {
            // Add new item
            $cart->items()->create([
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'variant_info' => $variant->weight,
                'quantity' => $request->quantity,
                'price' => $variant->price
            ]);
        }
        
        // Update cart total
        $cart->calculateTotal();
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart_count' => $cart->items()->sum('quantity')
        ]);
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = $this->getCart();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        $cartItem = $cart->items()->where('id', $itemId)->firstOrFail();
        
        if ($request->quantity == 0) {
            $cartItem->delete();
        } else {
            // Check stock (use variant stock if available)
            $stockLimit = $cartItem->variant 
                ? $cartItem->variant->stock_quantity 
                : $cartItem->product->stock_quantity;
                
            if ($stockLimit < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock available'
                ], 400);
            }
            
            $cartItem->update(['quantity' => $request->quantity]);
        }
        
        // Update cart total
        $cart->calculateTotal();
        
        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart_count' => $cart->items()->sum('quantity'),
            'total' => $cart->total_amount
        ]);
    }

    public function remove($itemId)
    {
        $cart = $this->getCart();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        $cartItem = $cart->items()->where('id', $itemId)->firstOrFail();
        $cartItem->delete();
        
        // Update cart total
        $cart->calculateTotal();
        
        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart_count' => $cart->items()->sum('quantity'),
            'total' => $cart->total_amount
        ]);
    }

    public function clear()
    {
        $cart = $this->getCart();
        
        if ($cart) {
            $cart->items()->delete();
            $cart->update(['total_amount' => 0]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    public function count()
    {
        $cart = $this->getCart();
        $count = $cart ? $cart->items()->sum('quantity') : 0;
        
        return response()->json(['count' => $count]);
    }

    private function getCart()
    {
        if (Auth::check()) {
            return Auth::user()->cart;
        } else {
            $sessionId = session()->getId();
            return Cart::where('session_id', $sessionId)->where('user_id', null)->first();
        }
    }

    private function getOrCreateCart()
    {
        if (Auth::check()) {
            return Auth::user()->cart ?: Auth::user()->carts()->create(['total_amount' => 0]);
        } else {
            $sessionId = session()->getId();
            return Cart::firstOrCreate(
                ['session_id' => $sessionId, 'user_id' => null],
                ['total_amount' => 0]
            );
        }
    }
}