<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        
        // Define weight options with their values and pricing multipliers
        $weightOptions = [
            [
                'weight' => '250gm',
                'weight_value' => 0.25,
                'weight_unit' => 'kg',
                'price_multiplier' => 1.0,
                'is_default' => true
            ],
            [
                'weight' => '500gm',
                'weight_value' => 0.5,
                'weight_unit' => 'kg',
                'price_multiplier' => 1.8,
                'is_default' => false
            ],
            [
                'weight' => '1kg',
                'weight_value' => 1.0,
                'weight_unit' => 'kg',
                'price_multiplier' => 3.4,
                'is_default' => false
            ],
            [
                'weight' => '3kg',
                'weight_value' => 3.0,
                'weight_unit' => 'kg',
                'price_multiplier' => 9.5,
                'is_default' => false
            ]
        ];

        foreach ($products as $product) {
            // Convert current USD prices to INR (approximate rate: 1 USD = 83 INR)
            $basePrice = $product->price * 83;
            $originalPrice = $product->original_price ? $product->original_price * 83 : null;
            
            foreach ($weightOptions as $option) {
                $variantPrice = round($basePrice * $option['price_multiplier'], 2);
                $variantOriginalPrice = $originalPrice ? round($originalPrice * $option['price_multiplier'], 2) : null;
                
                ProductVariant::create([
                    'product_id' => $product->id,
                    'weight' => $option['weight'],
                    'weight_value' => $option['weight_value'],
                    'weight_unit' => $option['weight_unit'],
                    'price' => $variantPrice,
                    'original_price' => $variantOriginalPrice,
                    'stock_quantity' => rand(10, 100),
                    'is_default' => $option['is_default'],
                    'is_available' => true
                ]);
            }
        }
    }
}