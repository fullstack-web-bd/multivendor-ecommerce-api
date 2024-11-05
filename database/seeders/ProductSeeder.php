<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'id' => 1,
                'name' => 'Samsung Galaxy S21',
                'slug' => 'samsung-galaxy-s21',
                'description' => 'Samsung Galaxy S21 is a great phone',
                'price' => 1000,
                'sale_price' => 900,
                'quantity' => 100,
                'brand_id' => 2,
                'category_id' => 2,
                'shop_id' => 1,
                'is_featured' => 1,
                'status' => 'published',
                'total_view' => 1000,
                'total_searched' => 500,
                'total_ordered' => 200,
                'created_by' => 1,
                'updated_by' => 1,
                'trashed_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Samsung Galaxy S22',
                'slug' => 'samsung-galaxy-s22',
                'description' => 'Samsung Galaxy S22 is a great phone',
                'price' => 1200,
                'sale_price' => 1100,
                'quantity' => 100,
                'brand_id' => 2,
                'category_id' => 2,
                'shop_id' => 1,
                'is_featured' => 1,
                'status' => 'published',
                'total_view' => 1000,
                'total_searched' => 500,
                'total_ordered' => 200,
                'created_by' => 1,
                'updated_by' => 1,
                'trashed_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Samsung Galaxy S23',
                'slug' => 'samsung-galaxy-s23',
                'description' => 'Samsung Galaxy S23 is a great phone',
                'price' => 1300,
                'sale_price' => 1200,
                'quantity' => 100,
                'brand_id' => 2,
                'category_id' => 2,
                'shop_id' => 1,
                'is_featured' => 1,
                'status' => 'published',
                'total_view' => 1000,
                'total_searched' => 500,
                'total_ordered' => 200,
                'created_by' => 1,
                'updated_by' => 1,
                'trashed_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Product::insert($products);
    }
}
