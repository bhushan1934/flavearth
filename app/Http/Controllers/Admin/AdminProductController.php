<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants']);
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        $products = $query->paginate(10);
        $categories = Category::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Log::info('Product store request', [
            'has_image' => $request->hasFile('image'),
            'all_files' => $request->allFiles()
        ]);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products',
            'scientific_name' => 'nullable|string|max:255',
            'botanical_family' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'required|string',
            'detailed_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'badge' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:20',
            'tags' => 'nullable|string',
            'stock_quantity' => 'nullable|integer|min:0',
            'in_stock' => 'nullable',
            'featured' => 'nullable',
            'specifications' => 'nullable|string',
            'nutritional_info' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'benefits' => 'nullable|string',
            'usage' => 'nullable|string',
            'market_info' => 'nullable|string',
            'variants' => 'nullable|array',
            'variants.*.weight' => 'required_with:variants|string',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.stock' => 'required_with:variants|integer|min:0',
        ], [
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'The image may not be greater than 5MB.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Each image must be a file of type: jpeg, png, jpg, gif, webp.',
            'images.*.max' => 'Each image may not be greater than 5MB.',
        ]);

        // Generate slug if not provided
        if (!isset($validated['slug']) || empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle main image upload
        if ($request->hasFile('image')) {
            try {
                $imagePath = $request->file('image')->store('products', 'public');
                $validated['image'] = '/storage/' . $imagePath;
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['image' => 'The image failed to upload. ' . $e->getMessage()]);
            }
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products', 'public');
                $images[] = '/storage/' . $imagePath;
            }
            $validated['images'] = $images;
        }

        // Convert comma-separated tags to array
        if (isset($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

        // Convert JSON fields from string to array
        foreach (['specifications', 'nutritional_info', 'ingredients', 'benefits', 'usage', 'market_info'] as $field) {
            if (isset($validated[$field]) && !empty($validated[$field])) {
                $validated[$field] = array_filter(array_map('trim', explode("\n", $validated[$field])));
            }
        }

        $validated['in_stock'] = $request->has('in_stock');
        $validated['featured'] = $request->has('featured');

        // Calculate discount percentage if original price is provided
        if (isset($validated['original_price']) && $validated['original_price'] > $validated['price']) {
            $validated['discount_percentage'] = round((($validated['original_price'] - $validated['price']) / $validated['original_price']) * 100);
        }

        $product = Product::create($validated);

        // Create variants if provided
        if (isset($request->variants)) {
            foreach ($request->variants as $variant) {
                if (!empty($variant['weight'])) {
                    // Parse weight to get value and unit
                    $weightParts = $this->parseWeight($variant['weight']);
                    
                    // Get stock value, handle empty string as 0
                    $stockQuantity = 0;
                    if (isset($variant['stock']) && $variant['stock'] !== '') {
                        $stockQuantity = intval($variant['stock']);
                    }
                    
                    $product->variants()->create([
                        'weight' => $variant['weight'],
                        'weight_value' => $weightParts['value'],
                        'weight_unit' => $weightParts['unit'],
                        'price' => $variant['price'],
                        'stock_quantity' => $stockQuantity,
                        'is_available' => $stockQuantity > 0
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'variants']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('variants');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $product->id,
            'scientific_name' => 'nullable|string|max:255',
            'botanical_family' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'required|string',
            'detailed_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'badge' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:20',
            'tags' => 'nullable|string',
            'stock_quantity' => 'nullable|integer|min:0',
            'in_stock' => 'nullable',
            'featured' => 'nullable',
            'specifications' => 'nullable|string',
            'nutritional_info' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'benefits' => 'nullable|string',
            'usage' => 'nullable|string',
            'market_info' => 'nullable|string',
        ]);

        // Generate slug if not provided
        if (!isset($validated['slug']) || empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle main image upload
        if ($request->hasFile('image')) {
            try {
                $imagePath = $request->file('image')->store('products', 'public');
                $validated['image'] = '/storage/' . $imagePath;
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['image' => 'The image failed to upload. ' . $e->getMessage()]);
            }
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products', 'public');
                $images[] = '/storage/' . $imagePath;
            }
            // Merge with existing images if needed
            $existingImages = $product->images ?? [];
            $validated['images'] = array_merge($existingImages, $images);
        }

        // Convert comma-separated tags to array
        if (isset($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

        // Convert JSON fields from string to array
        foreach (['specifications', 'nutritional_info', 'ingredients', 'benefits', 'usage', 'market_info'] as $field) {
            if (isset($validated[$field]) && !empty($validated[$field])) {
                $validated[$field] = array_filter(array_map('trim', explode("\n", $validated[$field])));
            }
        }

        $validated['in_stock'] = $request->has('in_stock');
        $validated['featured'] = $request->has('featured');

        // Calculate discount percentage if original price is provided
        if (isset($validated['original_price']) && $validated['original_price'] > $validated['price']) {
            $validated['discount_percentage'] = round((($validated['original_price'] - $validated['price']) / $validated['original_price']) * 100);
        }

        $product->update($validated);

        // Handle variants update
        if ($request->has('variants')) {
            // Get existing variants
            $existingVariants = $product->variants->keyBy('weight');
            $processedWeights = [];
            
            // Update or create variants
            foreach ($request->variants as $variant) {
                if (!empty($variant['weight']) && !empty($variant['price'])) {
                    // Parse weight to get value and unit
                    $weightParts = $this->parseWeight($variant['weight']);
                    
                    // Get stock value - explicitly handle 0, empty string, and null
                    $stockQuantity = 0;
                    if (array_key_exists('stock', $variant)) {
                        $stockQuantity = (int) $variant['stock'];
                    }
                    
                    $variantData = [
                        'weight' => $variant['weight'],
                        'weight_value' => $weightParts['value'],
                        'weight_unit' => $weightParts['unit'],
                        'price' => (float) $variant['price'],
                        'stock_quantity' => $stockQuantity,
                        'is_available' => $stockQuantity > 0
                    ];
                    
                    // Check if variant exists
                    if ($existingVariants->has($variant['weight'])) {
                        // Update existing variant
                        $existingVariants->get($variant['weight'])->update($variantData);
                    } else {
                        // Create new variant
                        $product->variants()->create($variantData);
                    }
                    
                    $processedWeights[] = $variant['weight'];
                }
            }
            
            // Delete variants that are no longer in the form
            // Only delete if they are not referenced in orders
            foreach ($existingVariants as $weight => $existingVariant) {
                if (!in_array($weight, $processedWeights)) {
                    // Check if variant is used in any orders
                    if ($existingVariant->orderItems()->count() == 0) {
                        $existingVariant->delete();
                    } else {
                        // Mark as unavailable instead of deleting
                        $existingVariant->update([
                            'is_available' => false,
                            'stock_quantity' => 0
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
    
    /**
     * Toggle variant stock availability
     */
    public function toggleVariantStock(Product $product, ProductVariant $variant)
    {
        // Ensure the variant belongs to the product
        if ($variant->product_id !== $product->id) {
            return response()->json(['success' => false, 'message' => 'Invalid variant'], 403);
        }
        
        // Toggle availability or set stock to 0
        if ($variant->stock_quantity > 0) {
            // If has stock, set to 0 to make out of stock
            $variant->stock_quantity = 0;
            $variant->is_available = false;
        } else {
            // If out of stock, don't change quantity but toggle availability
            $variant->is_available = !$variant->is_available;
        }
        
        $variant->save();
        
        return response()->json([
            'success' => true,
            'is_available' => $variant->is_available,
            'stock_quantity' => $variant->stock_quantity
        ]);
    }
    
    /**
     * Parse weight string to extract numerical value and unit
     */
    private function parseWeight($weight)
    {
        // Handle empty weight
        if (empty($weight)) {
            return [
                'value' => 0,
                'unit' => 'gm'
            ];
        }
        
        // Remove spaces and convert to lowercase
        $weight = strtolower(str_replace(' ', '', $weight));
        
        // Extract number and unit
        preg_match('/^(\d+(?:\.\d+)?)(kg|gm|g|gram|grams|kilogram|kilograms)?$/i', $weight, $matches);
        
        $value = isset($matches[1]) ? floatval($matches[1]) : 0;
        $unit = isset($matches[2]) ? $matches[2] : 'gm';
        
        // Normalize units
        if (in_array($unit, ['g', 'gram', 'grams'])) {
            $unit = 'gm';
        } elseif (in_array($unit, ['kilogram', 'kilograms'])) {
            $unit = 'kg';
        }
        
        return [
            'value' => $value,
            'unit' => $unit
        ];
    }
}