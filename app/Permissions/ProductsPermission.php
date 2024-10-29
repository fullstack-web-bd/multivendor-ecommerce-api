<?php

namespace App\Permissions;

class ProductsPermission
{
    public const VIEW_PRODUCTS = 'products.view';
    public const CREATE_PRODUCTS = 'products.create';
    public const UPDATE_PRODUCTS = 'products.update';
    public const DELETE_PRODUCTS = 'products.delete';

    protected function getErrorMessages(string $permissionKey): string
    {
        return match ($permissionKey) {
            self::VIEW_PRODUCTS => __('You do not have permission to view products.'),
            self::CREATE_PRODUCTS => __('You do not have permission to create products.'),
            self::UPDATE_PRODUCTS => __('You do not have permission to update products.'),
            self::DELETE_PRODUCTS => __('You do not have permission to delete products.'),
            default => __('You do not have permission to perform this action.'),
        };
    }

    public function canViewProducts()
    {
        if (!request()->user()->hasAnyPermission([self::VIEW_PRODUCTS])) {
            throw new \Exception($this->getErrorMessages(self::VIEW_PRODUCTS), 403);
        }
    }

    public function canCreateProducts()
    {
        if (!request()->user()->hasAnyPermission([self::CREATE_PRODUCTS])) {
            throw new \Exception($this->getErrorMessages(self::CREATE_PRODUCTS), 403);
        }
    }

    public function canUpdateProducts()
    {
        if (!request()->user()->hasAnyPermission([self::UPDATE_PRODUCTS])) {
            throw new \Exception($this->getErrorMessages(self::UPDATE_PRODUCTS), 403);
        }
    }

    public function canDeleteProducts()
    {
        if (!request()->user()->hasAnyPermission([self::DELETE_PRODUCTS])) {
            throw new \Exception($this->getErrorMessages(self::DELETE_PRODUCTS), 403);
        }
    }
}
