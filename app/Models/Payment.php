<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'status',
        'amount',
    ];

    /**
     * Get the order that owns the payment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the payment method that owns the payment.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}