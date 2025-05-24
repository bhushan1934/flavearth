<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Whole Spices',
            'slug' => 'whole-spices',
            'description' => 'Premium quality whole spices that retain their natural oils and flavors. Perfect for tempering and grinding fresh.',
            'image' => 'images/categories/whole-spices.jpg',
            'featured' => true,
            'sort_order' => 1
        ]);

        Category::create([
            'name' => 'Ground Spices',
            'slug' => 'ground-spices',
            'description' => 'Freshly ground spices processed using traditional methods to preserve their authentic taste and aroma.',
            'image' => 'images/categories/ground-spices.jpg',
            'featured' => true,
            'sort_order' => 2
        ]);

        Category::create([
            'name' => 'Spice Blends',
            'slug' => 'spice-blends',
            'description' => 'Expertly crafted spice blends combining multiple spices in perfect proportions for specific dishes.',
            'image' => 'images/categories/spice-blends.jpg',
            'featured' => true,
            'sort_order' => 3
        ]);
    }
}
