<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'waybill',
        'courier_name',
        'status',
        'pickup_location',
        'shipment_data',
        'tracking_data',
        'shipped_at',
        'delivered_at',
        'packing_slip_url',
        'notes'
    ];

    protected $casts = [
        'shipment_data' => 'array',
        'tracking_data' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'secondary',
            'pickup_scheduled' => 'info',
            'picked' => 'primary',
            'in_transit' => 'warning',
            'out_for_delivery' => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'rto' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getFormattedStatusAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'pickup_scheduled' => 'Pickup Scheduled',
            'picked' => 'Picked Up',
            'in_transit' => 'In Transit',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'rto' => 'Return to Origin'
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }
}