<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\ShopService;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function __construct(
        private readonly ShopService $shopService,
    ) {
    }

    public function run(): void
    {
        $superAdminUser = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
        ]);

        // User Superadmin.
        $superAdminUser->assignRole('Superadmin');

        // Create Vendor and Shop.
        $vendorUser = User::create([
            'name' => 'Vendor',
            'email' => 'vendor@example.com',
            'password' => bcrypt('password'),
        ]);
        $vendorUser->assignRole('Vendor');
        $shop = $this->shopService->createShop([
            'name' => 'Superadmin Shop',
            'owner_id' => $vendorUser->id,
        ]);
        $vendorUser->shop_id = $shop->id;
        $vendorUser->save();

        // Create Customer.
        $customerUser = User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
        ]);
        $customerUser->assignRole('Customer');
    }
}