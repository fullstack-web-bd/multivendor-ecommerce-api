<?php

namespace Database\Seeders;

use App\Models\Permission;
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

        $permissions = [
            'dashboard' => [
                'dashboard.view',
                'dashboard.advance_report',
            ],
            'products' => [
                'products.view',
                'products.create',
                'products.update',
                'products.delete',
            ]
        ];

        foreach ($permissions as $groupName => $permission) {
            foreach ($permission as $permissionName) {
                $permission = Permission::create([
                    'name' => $permissionName,
                    'guard_name' => 'api',
                    'group_name' => ucfirst($groupName),
                ]);
                $superAdminRole->givePermissionTo($permissionName);
            }
        }
    }
}