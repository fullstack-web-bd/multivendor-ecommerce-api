<?php

namespace Database\Seeders;

use App\Permissions\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::create([
            'name' => 'Superadmin',
            'guard_name' => 'api',
        ]);

        foreach (Permissions::getPermissionsWithGroup() as $groupName => $permission) {
            foreach ($permission as $permissionName) {
                Permissions::createApiPermission($permissionName, $groupName);
                $superAdminRole->givePermissionTo($permissionName);
            }
        }
    }
}