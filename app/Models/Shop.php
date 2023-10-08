<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'address',
        'phone',
        'email',
        'logo',
        'facebook_link',
        'youtube_link',
    ];

    /**
     * Get the owner that owns the shop.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}