<?php

namespace App\Models;

use App\Traits\Dropdownable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, Dropdownable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image'
    ];

    /**
     * Get the products for the brand.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}