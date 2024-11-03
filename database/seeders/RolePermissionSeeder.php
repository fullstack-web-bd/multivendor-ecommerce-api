<?php

namespace Database\Seeders;

use App\Permissions\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Superadmin' => Role::create(['name' => 'Superadmin', 'guard_name' => 'api']),
            'Vendor' => Role::create(['name' => 'Vendor', 'guard_name' => 'api']),
            'Customer' => Role::create(['name' => 'Customer', 'guard_name' => 'api']),
        ];

        // Create permissions.
        foreach (Permissions::getPermissionsWithGroup() as $groupName => $permission) {
            foreach ($permission as $permissionName) {
                Permissions::createApiPermission($permissionName, $groupName);
            }
        }

        // Assign permissions to roles.
        $roles['Superadmin']->givePermissionTo(Permissions::getAllPermissions());
        $roles['Vendor']->givePermissionTo(Permissions::getVendorPermissions());
        $roles['Customer']->givePermissionTo(Permissions::getCustomerPermissions());
    }
}