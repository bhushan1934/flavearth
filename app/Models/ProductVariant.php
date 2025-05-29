<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'weight',
        'weight_value',
        'weight_unit',
        'price',
        'original_price',
        'stock_quantity',
        'is_default',
        'is_available'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'weight_value' => 'decimal:3',
        'stock_quantity' => 'integer',
        'is_default' => 'boolean',
        'is_available' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'variant_id');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return $this->original_price ? '₹' . number_format($this->original_price, 2) : null;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }

    public function getInStockAttribute()
    {
        return $this->is_available && $this->stock_quantity > 0;
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('stock_quantity', '>', 0);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}