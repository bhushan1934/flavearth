<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlistItems = Auth::user()->wishlist()->with('product')->get();
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Check if already in wishlist
        $exists = Auth::user()->wishlist()
            ->where('product_id', $product->id)
            ->exists();
        
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist'
            ]);
        }

        Auth::user()->wishlist()->create([
            'product_id' => $product->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist',
            'wishlist_count' => Auth::user()->wishlist()->count()
        ]);
    }

    public function remove($productId)
    {
        $wishlistItem = Auth::user()->wishlist()
            ->where('product_id', $productId)
            ->firstOrFail();
        
        $wishlistItemId = $wishlistItem->id;
        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist',
            'wishlistItemId' => $wishlistItemId,
            'wishlist_count' => Auth::user()->wishlist()->count()
        ]);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->product_id;
        
        $wishlistItem = Auth::user()->wishlist()
            ->where('product_id', $productId)
            ->first();
        
        if ($wishlistItem) {
            $wishlistItem->delete();
            $added = false;
            $message = 'Product removed from wishlist';
        } else {
            Auth::user()->wishlist()->create([
                'product_id' => $productId
            ]);
            $added = true;
            $message = 'Product added to wishlist';
        }

        return response()->json([
            'success' => true,
            'added' => $added,
            'message' => $message,
            'wishlist_count' => Auth::user()->wishlist()->count()
        ]);
    }

    public function count()
    {
        $count = Auth::user()->wishlist()->count();
        return response()->json(['count' => $count]);
    }
}