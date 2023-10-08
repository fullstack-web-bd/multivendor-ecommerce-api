<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $superAdminUser = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
        ]);

        // User Superadmin.
        $superAdminUser->assignRole('Superadmin');

        // User Seller.
        // User Customer.
    }
}