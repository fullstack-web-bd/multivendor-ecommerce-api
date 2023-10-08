<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'name',
        'address',
        'city',
        'state_id',
        'country_id',
        'zip_code',
        'phone',
        'email',
        'is_default',
        'is_billing',
        'is_shipping',
        'type',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order that owns the address.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}