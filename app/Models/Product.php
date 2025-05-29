<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'scientific_name',
        'botanical_family',
        'short_description',
        'description',
        'detailed_description',
        'price',
        'original_price',
        'discount_percentage',
        'image',
        'images',
        'rating',
        'review_count',
        'badge',
        'badge_color',
        'tags',
        'in_stock',
        'stock_quantity',
        'featured',
        'category_id',
        'specifications',
        'nutritional_info',
        'ingredients',
        'benefits',
        'usage',
        'market_info'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'decimal:1',
        'images' => 'array',
        'tags' => 'array',
        'specifications' => 'array',
        'nutritional_info' => 'array',
        'ingredients' => 'array',
        'benefits' => 'array',
        'usage' => 'array',
        'market_info' => 'array',
        'in_stock' => 'boolean',
        'featured' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true);
    }

    public function availableVariants()
    {
        return $this->hasMany(ProductVariant::class)->available()->orderBy('weight_value');
    }

    public function getMinPriceAttribute()
    {
        return $this->variants()->min('price') ?? $this->price;
    }

    public function getMaxPriceAttribute()
    {
        return $this->variants()->max('price') ?? $this->price;
    }

    public function getPriceRangeAttribute()
    {
        $min = $this->min_price;
        $max = $this->max_price;
        
        if ($min == $max) {
            return '₹' . number_format($min, 2);
        }
        
        return '₹' . number_format($min, 2) . ' - ₹' . number_format($max, 2);
    }
    
    public function getDisplay250gmPriceAttribute()
    {
        // Try to find 250gm variant
        $variant250gm = $this->variants()
            ->where('weight', '250gm')
            ->orWhere('weight', '250g')
            ->orWhere('weight', '250 gm')
            ->orWhere('weight', '250 g')
            ->first();
        
        if ($variant250gm) {
            return '₹' . number_format($variant250gm->price, 2);
        }
        
        // If no 250gm variant, return default variant price or product price
        if ($this->defaultVariant) {
            return '₹' . number_format($this->defaultVariant->price, 2);
        }
        
        return '₹' . number_format($this->price, 2);
    }
    
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        // If it's a full URL (http/https)
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        
        // If it's a storage path
        if (str_starts_with($this->image, '/storage/')) {
            return asset($this->image);
        }
        
        // If it already contains a path (has forward slash), just prepend asset
        if (str_contains($this->image, '/')) {
            return asset($this->image);
        }
        
        // If it's just a filename, assume it's in public/images/products/
        return asset('images/products/' . $this->image);
    }
    
    public function getImageUrlsAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }
        
        return array_map(function($image) {
            // If it's a full URL (http/https)
            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                return $image;
            }
            
            // If it's a storage path
            if (str_starts_with($image, '/storage/')) {
                return asset($image);
            }
            
            // If it already contains a path (has forward slash), just prepend asset
            if (str_contains($image, '/')) {
                return asset($image);
            }
            
            // If it's just a filename, assume it's in public/images/products/
            return asset('images/products/' . $image);
        }, $this->images);
    }
    
    /**
     * Generate SEO-friendly keywords based on product name and category
     */
    public function getSeoKeywordsAttribute()
    {
        $keywords = [];
        $name = strtolower($this->name);
        
        // Spice-specific keyword mappings
        $spiceKeywords = [
            'red chilli' => ['red chilli powder', 'red chili powder', 'lal mirch powder', 'cayenne pepper', 'hot chili powder'],
            'turmeric' => ['turmeric powder', 'haldi powder', 'organic turmeric', 'curcumin powder', 'yellow turmeric'],
            'coriander' => ['coriander powder', 'dhania powder', 'ground coriander', 'coriander seeds powder'],
            'cumin' => ['cumin powder', 'jeera powder', 'ground cumin', 'cumin seeds powder'],
            'garam masala' => ['garam masala powder', 'whole garam masala', 'indian spice mix', 'masala powder'],
            'cardamom' => ['green cardamom', 'elaichi', 'cardamom pods', 'aromatic cardamom'],
            'cinnamon' => ['cinnamon sticks', 'dalchini', 'ceylon cinnamon', 'organic cinnamon'],
            'black pepper' => ['black pepper powder', 'kali mirch', 'ground black pepper', 'pepper powder'],
            'cloves' => ['whole cloves', 'laung', 'organic cloves', 'aromatic cloves'],
            'nutmeg' => ['nutmeg powder', 'jaiphal', 'ground nutmeg', 'whole nutmeg']
        ];
        
        // Base keywords for all products
        $baseKeywords = [
            'buy ' . $name . ' online',
            'premium ' . $name,
            'organic ' . $name,
            'authentic ' . $name,
            $name . ' price',
            $name . ' online shopping',
            'best ' . $name . ' brand',
            'pure ' . $name,
            'natural ' . $name
        ];
        
        $keywords = array_merge($keywords, $baseKeywords);
        
        // Add spice-specific keywords
        foreach ($spiceKeywords as $spice => $variants) {
            if (str_contains($name, $spice)) {
                $keywords = array_merge($keywords, $variants);
                foreach ($variants as $variant) {
                    $keywords[] = 'buy ' . $variant . ' online';
                    $keywords[] = 'premium ' . $variant;
                    $keywords[] = 'organic ' . $variant;
                }
                break;
            }
        }
        
        // Add Indian spice context
        $keywords[] = 'indian spices online';
        $keywords[] = 'traditional spices';
        $keywords[] = 'flavearth spices';
        $keywords[] = 'spice store india';
        
        return implode(', ', array_unique($keywords));
    }
    
    /**
     * Generate SEO-optimized description
     */
    public function getSeoDescriptionAttribute()
    {
        $name = strtolower($this->name);
        
        $descriptions = [
            'red chilli' => 'Buy premium red chilli powder online at Flavearth. Authentic, hot and flavorful red chili powder (lal mirch) sourced from the finest peppers. Perfect for Indian cooking. Free delivery across India.',
            'turmeric' => 'Buy premium turmeric powder (haldi) online at Flavearth. Pure, organic turmeric with high curcumin content. Authentic Indian turmeric powder for cooking and health. Fast delivery across India.',
            'coriander' => 'Buy premium coriander powder (dhania) online at Flavearth. Fresh, aromatic ground coriander seeds powder. Essential spice for Indian cooking. Best quality, pure and natural.',
            'cumin' => 'Buy premium cumin powder (jeera) online at Flavearth. Aromatic, fresh ground cumin seeds powder. Essential for Indian cuisine. Authentic quality with fast delivery.',
            'garam masala' => 'Buy premium garam masala powder online at Flavearth. Traditional Indian spice blend with authentic taste. Perfect mix of aromatic spices for authentic Indian cooking.'
        ];
        
        // Check for specific spice descriptions
        foreach ($descriptions as $spice => $desc) {
            if (str_contains($name, $spice)) {
                return $desc;
            }
        }
        
        // Default description
        return "Buy premium {$name} online at Flavearth. Authentic, high-quality {$name} sourced directly from Indian farmers. Best price, pure quality, fast delivery across India.";
    }
}
