<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Ground Spices category (will be created by CategorySeeder)
        $groundSpicesCategory = Category::where('slug', 'ground-spices')->first();

        // Create Red Chili Powder
        Product::create([
            'name' => 'Red Chili Powder',
            'slug' => 'red-chili-powder',
            'scientific_name' => 'Capsicum annuum',
            'botanical_family' => 'Solanaceae',
            'short_description' => 'Premium grade red chili powder with perfect heat balance and vibrant color.',
            'description' => 'A popular spice used to add heat, color, and flavor to dishes. Hand-picked chilies, sun-dried and stone-ground to preserve their rich flavor and vibrant color. Our red chili powder is sourced from the finest farms and processed using traditional methods to ensure maximum potency and authentic taste.',
            'detailed_description' => 'Our premium red chili powder is made from carefully selected Capsicum annuum chilies that are known for their perfect balance of heat and flavor. Each batch is processed in small quantities to maintain consistency and quality. The chilies are sun-dried naturally to preserve their essential oils and capsaicin content, then stone-ground to achieve the perfect texture. This spice is integral to Indian, Mexican, and Thai cuisines, providing not just heat but complex flavor profiles.',
            'price' => 7.99,
            'original_price' => 9.99,
            'discount_percentage' => 20,
            'image' => 'images/products/IMG_5007.jpg',
            'images' => [
                'images/products/IMG_5007.jpg',
                'images/categories/rc.png'
            ],
            'rating' => 4.5,
            'review_count' => 124,
            'badge' => 'Bestseller',
            'badge_color' => 'danger',
            'tags' => ['Organic', 'Medium Heat', '100g', 'Rich in Capsaicin'],
            'in_stock' => true,
            'stock_quantity' => 50,
            'featured' => true,
            'category_id' => $groundSpicesCategory->id ?? null,
            'specifications' => [
                'Weight' => '100g',
                'Heat Level' => 'Medium (5,000-10,000 SHU)',
                'Origin' => 'India',
                'Botanical Family' => 'Solanaceae',
                'Scientific Name' => 'Capsicum annuum',
                'Processing' => 'Sun-dried & Stone Ground',
                'Cultivation Area' => '792,000 hectares in India',
                'Annual Production' => '1,376,000 million tons',
                'Shelf Life' => '24 months',
                'Storage' => 'Cool, dry place away from sunlight'
            ],
            'nutritional_info' => [
                'Calories' => '40 kcal per serving',
                'Total Carbohydrates' => '9g',
                'Dietary Fiber' => '1.5g',
                'Protein' => '2g',
                'Fat' => '0.2g',
                'Calcium' => 'Present',
                'Iron' => 'Present',
                'Magnesium' => 'Present',
                'Vitamin A' => 'High content',
                'Vitamin C' => 'High content',
                'Vitamin B-6' => 'Present'
            ],
            'ingredients' => ['100% Pure Red Chili Powder (Capsicum annuum)'],
            'benefits' => [
                'Rich in Vitamins A and C for immune support',
                'Contains capsaicin with potential medicinal properties',
                'Boosts metabolism and may aid weight loss',
                'Supports cardiovascular health',
                'Natural antioxidant and anti-inflammatory properties',
                'Can be used topically for muscle and joint pain relief',
                'Adds authentic flavor and color to dishes',
                'No artificial colors or preservatives'
            ],
            'usage' => [
                'Essential for Indian, Mexican, and Thai cuisines',
                'Perfect for curries, gravies, and sauces',
                'Ideal for marinades and meat rubs',
                'Great for spice blends and masalas',
                'Use in food processing and preservation',
                'Add to soups and stews for heat and flavor',
                'Sprinkle on snacks and street food',
                'Use sparingly due to high potency'
            ],
            'market_info' => [
                '70% consumed domestically in India',
                '30% exported globally',
                'One of the most traded spices worldwide'
            ]
        ]);

        // Create Turmeric Powder
        Product::create([
            'name' => 'Turmeric Powder',
            'slug' => 'turmeric-powder',
            'scientific_name' => 'Curcuma longa',
            'botanical_family' => 'Zingiberaceae',
            'short_description' => 'High-curcumin turmeric powder with exceptional flavor, vibrant golden color, and health benefits.',
            'description' => 'Premium quality turmeric powder with vibrant golden color and earthy flavor. Traditionally harvested and processed to preserve its natural potency and curcumin content. Our turmeric powder is organically grown and contains high levels of curcumin for maximum health benefits.',
            'detailed_description' => 'Our premium turmeric powder is sourced from the finest Curcuma longa rhizomes known for their high curcumin content (3-4%). Each batch is carefully processed to retain the natural oils and active compounds that make turmeric so beneficial. The vibrant golden color and earthy aroma are testimony to its superior quality. India produces over 80% of the world\'s turmeric, and our product represents the best of this traditional spice.',
            'price' => 6.99,
            'original_price' => 8.99,
            'discount_percentage' => 22,
            'image' => 'images/products/IMG_5008.jpg',
            'images' => [
                'images/products/IMG_5008.jpg',
                'images/categories/turmeric.png'
            ],
            'rating' => 5.0,
            'review_count' => 89,
            'badge' => 'Popular',
            'badge_color' => 'warning',
            'tags' => ['Organic', 'High Curcumin', '100g', 'Golden Color'],
            'in_stock' => true,
            'stock_quantity' => 35,
            'featured' => true,
            'category_id' => $groundSpicesCategory->id ?? null,
            'specifications' => [
                'Weight' => '100g',
                'Curcumin Content' => '3-4%',
                'Origin' => 'India',
                'Botanical Family' => 'Zingiberaceae',
                'Scientific Name' => 'Curcuma longa',
                'Processing' => 'Organically grown & processed',
                'Color' => 'Vibrant golden',
                'Global Production' => 'India produces 80% worldwide',
                'Shelf Life' => '24 months',
                'Storage' => 'Airtight container in cool, dry place'
            ],
            'nutritional_info' => [
                'Calories' => '40 kcal per serving',
                'Carbohydrates' => '9g',
                'Dietary Fiber' => '2g',
                'Calcium' => 'Present',
                'Iron' => 'Present',
                'Potassium' => 'Present',
                'Vitamin C' => 'Present',
                'Vitamin E' => 'Present',
                'Curcumin' => '3-4% active compound'
            ],
            'ingredients' => ['100% Pure Organic Turmeric Powder (Curcuma longa)'],
            'benefits' => [
                'Rich in antioxidants for cellular protection',
                'Powerful anti-inflammatory properties',
                'Supports brain health and improves memory',
                'May help prevent Alzheimer\'s disease',
                'Supports heart health and blood circulation',
                'Natural skin brightening and anti-aging',
                'Aids in digestion and gut health',
                'May help manage diabetes',
                'Supports joint health and reduces arthritis symptoms',
                'Enhances immune system function'
            ],
            'usage' => [
                'Add to curries for color and flavor',
                'Mix with milk for golden milk latte',
                'Use in marinades for meat and vegetables',
                'Add to rice dishes for golden color',
                'Make turmeric tea for health benefits',
                'Use in face masks for glowing skin',
                'Add to smoothies and juices',
                'Essential in Indian and Middle Eastern cuisine'
            ],
            'market_info' => [
                'India produces 80% of global turmeric',
                'Growing demand in health and wellness sector',
                'Increasing use in cosmetics and pharmaceuticals'
            ]
        ]);
    }
}