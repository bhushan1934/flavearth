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
}
