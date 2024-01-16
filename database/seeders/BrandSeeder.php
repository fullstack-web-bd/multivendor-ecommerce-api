<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'id' => 1,
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Sony',
                'slug' => 'sony',
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Brand::insert($brands);
    }
}
