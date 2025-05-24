<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'defaultVariant', 'variants'])->get();
        return view('shop.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'variants' => function($query) {
                $query->where('is_available', true)->orderBy('weight_value');
            }])
            ->firstOrFail();
        
        // Get the default variant or first available variant
        $defaultVariant = $product->variants->where('is_default', true)->first() 
                         ?? $product->variants->first();
        
        // Get related products from the same category (exclude current product)
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with(['defaultVariant', 'variants'])
            ->limit(4)
            ->get();
        
        // If not enough related products in same category, get from other categories
        if ($relatedProducts->count() < 4) {
            $additionalProducts = Product::where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->with(['defaultVariant', 'variants'])
                ->limit(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->concat($additionalProducts);
        }
        
        return view('shop.show', compact('product', 'defaultVariant', 'relatedProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}