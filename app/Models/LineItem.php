<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'discount',
        'tax',
        'shipping',
        'grand_total',
        'shop_id',
    ];

    /**
     * Get the order that owns the line item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the line item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the shop that owns the line item.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}