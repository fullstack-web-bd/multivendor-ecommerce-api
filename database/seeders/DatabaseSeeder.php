<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run initial seeders.
        $this->call([
            CategorySeeder::class,
            BrandSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);

        // Install Passport.
        $this->command->call('passport:install');

        // Clear cache.
        $this->command->call('cache:clear');
    }
}