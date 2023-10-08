<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total',
        'discount',
        'tax',
        'shipping',
        'grand_total',
        'shipping_address_id',
        'billing_address_id',
        'order_note',
        'payment_method',
        'payment_status',
        'tracking_number',
        'tracking_url',
        'shipping_carrier',
        'parent_order_id',
        'shop_id',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping address that owns the order.
     */
    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    /**
     * Get the billing address that owns the order.
     */
    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    /**
     * Get the shop that owns the order.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get parent order.
     */
    public function parentOrder()
    {
        return $this->belongsTo(Order::class, 'parent_order_id');
    }

    /**
     * Get child orders.
     */
    public function childOrders()
    {
        return $this->hasMany(Order::class, 'parent_order_id');
    }

    /**
     * Get order line items
     */
    public function lineItems()
    {
        return $this->hasMany(LineItem::class);
    }

    /**
     * Get order payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}