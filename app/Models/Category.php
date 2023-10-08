<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'slug',
        'description',
    ];

    /**
     * Get the products for the brand.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}