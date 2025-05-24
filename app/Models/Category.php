<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'featured',
        'sort_order'
    ];

    protected $casts = [
        'featured' => 'boolean'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
