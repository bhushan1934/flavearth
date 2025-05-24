<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured products from database
        $featuredProducts = Product::where('featured', true)->with(['category', 'defaultVariant', 'variants'])->get();
        
        // Get featured categories from database
        $categories = Category::where('featured', true)->orderBy('sort_order')->get();
        
        return view('home', compact('featuredProducts', 'categories'));
    }
}