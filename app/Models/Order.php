<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'gst',
        'shipping_cost',
        'total',
        'total_amount',
        'shipping_method',
        'shipping_address',
        'billing_address',
        'status',
        'payment_status',
        'payment_method',
        'razorpay_payment_id',
        'razorpay_order_id',
        'tracking_number',
        'notes',
        'discount',
        'tax',
        'shipping'
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'subtotal' => 'decimal:2',
        'gst' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }
    
    // public function statusHistory()
    // {
    //     // This would require a separate order_status_history table
    //     // For now, we'll return an empty collection
    //     return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    // }
}
