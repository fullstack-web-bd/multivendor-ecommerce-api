<?php

namespace App\Permissions;

class BrandsPermission
{
    public const VIEW_BRANDS = 'brands.view';
    public const CREATE_BRANDS = 'brands.create';
    public const UPDATE_BRANDS = 'brands.update';
    public const DELETE_BRANDS = 'brands.delete';

    protected function getErrorMessages(string $permissionKey): string
    {
        return match ($permissionKey) {
            self::VIEW_BRANDS => __('You do not have permission to view brands.'),
            self::CREATE_BRANDS => __('You do not have permission to create brands.'),
            self::UPDATE_BRANDS => __('You do not have permission to update brands.'),
            self::DELETE_BRANDS => __('You do not have permission to delete brands.'),
            default => __('You do not have permission to perform this action.'),
        };
    }

    public function canViewBrands()
    {
        if (!request()->user()->hasAnyPermission([self::VIEW_BRANDS])) {
            throw new \Exception($this->getErrorMessages(self::VIEW_BRANDS), 403);
        }
    }

    public function canCreateBrands()
    {
        if (!request()->user()->hasAnyPermission([self::CREATE_BRANDS])) {
            throw new \Exception($this->getErrorMessages(self::CREATE_BRANDS), 403);
        }
    }

    public function canUpdateBrands()
    {
        if (!request()->user()->hasAnyPermission([self::UPDATE_BRANDS])) {
            throw new \Exception($this->getErrorMessages(self::UPDATE_BRANDS), 403);
        }
    }

    public function canDeleteBrands()
    {
        if (!request()->user()->hasAnyPermission([self::DELETE_BRANDS])) {
            throw new \Exception($this->getErrorMessages(self::DELETE_BRANDS), 403);
        }
    }
}
