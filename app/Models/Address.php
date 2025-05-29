<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'name',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'pincode',
        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
