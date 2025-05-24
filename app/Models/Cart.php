<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    protected $fillable = ['user_id', 'session_id', 'total_amount'];

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function calculateTotal()
    {
        $total = $this->items()->sum(DB::raw('quantity * price'));
        
        $this->update(['total_amount' => $total]);
        
        return $total;
    }
}