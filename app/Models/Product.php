<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'quantity',
        'brand_id',
        'category_id',
        'shop_id',
        'is_featured',
        'status',
        'total_view',
        'total_searched',
        'total_ordered',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the brand that owns the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class)->select('id', 'name', 'slug');
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class)->select('id', 'name', 'slug');
    }

    /**
     * Get the shop that owns the product.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class)->select('id', 'name', 'slug');
    }

    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImages::class, 'product_id')->select('id', 'product_id', 'file', 'is_featured', 'order');
    }

    /**
     * Get featured image for the product.
     */
    public function featuredImage()
    {
        return $this->hasOne(ProductImages::class)->where('is_featured', 1)->select('id', 'product_id', 'file', 'is_featured', 'order');
    }
}