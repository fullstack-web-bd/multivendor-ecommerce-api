<?php

namespace App\Permissions;

use App\Models\Permission;

class Permissions
{
    public static function getPermissionsWithGroup()
    {
        return [
            'dashboard' => [
                DashboardPermission::VIEW_DASHBOARD,
                DashboardPermission::VIEW_DASHBOARD_ADVANCE_REPORT,
            ],
            'products' => [
                ProductsPermission::VIEW_PRODUCTS,
                ProductsPermission::CREATE_PRODUCTS,
                ProductsPermission::UPDATE_PRODUCTS,
                ProductsPermission::DELETE_PRODUCTS,
            ],
            'categories' => [
                CategoriesPermission::VIEW_CATEGORIES,
                CategoriesPermission::CREATE_CATEGORIES,
                CategoriesPermission::UPDATE_CATEGORIES,
                CategoriesPermission::DELETE_CATEGORIES,
            ],
            'brands' => [
                BrandsPermission::VIEW_BRANDS,
                BrandsPermission::CREATE_BRANDS,
                BrandsPermission::UPDATE_BRANDS,
                BrandsPermission::DELETE_BRANDS,
            ],
            'shops' => [
                ShopsPermission::VIEW_SHOPS,
                ShopsPermission::CREATE_SHOPS,
                ShopsPermission::UPDATE_SHOPS,
                ShopsPermission::DELETE_SHOPS,
            ],
        ];
    }

    public static function getAllPermissions(): array
    {
        $permissions = self::getPermissionsWithGroup();
        $allPermissions = [];

        foreach ($permissions as $groupName => $permission) {
            foreach ($permission as $permissionName) {
                $allPermissions[] = $permissionName;
            }
        }

        return $allPermissions;
    }

    public static function getCustomerPermissions(): array
    {
        return [
            DashboardPermission::VIEW_DASHBOARD,

            // view orders.
            // view profile, edit profile.
        ];
    }

    public static function getVendorPermissions(): array
    {
        return [
            DashboardPermission::VIEW_DASHBOARD,

            // Products permissions.
            ProductsPermission::VIEW_PRODUCTS,
            ProductsPermission::CREATE_PRODUCTS,
            ProductsPermission::UPDATE_PRODUCTS,
            ProductsPermission::DELETE_PRODUCTS,

            // Shops permissions.
            ShopsPermission::VIEW_SHOPS,
        ];
    }

    public static function getPermissionsByGroup(string $groupName)
    {
        $permissions = self::getPermissionsWithGroup();

        return $permissions[$groupName] ?? [];
    }

    public static function createApiPermission(string $permissionName, string $groupName): Permission
    {
        return Permission::create([
            'name' => $permissionName,
            'guard_name' => 'api',
            'group_name' => ucfirst($groupName),
        ]);
    }
}
