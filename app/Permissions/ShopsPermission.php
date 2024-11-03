<?php

namespace App\Permissions;

class ShopsPermission
{
    public const VIEW_SHOPS = 'shops.view';
    public const CREATE_SHOPS = 'shops.create';
    public const UPDATE_SHOPS = 'shops.update';
    public const DELETE_SHOPS = 'shops.delete';

    protected function getErrorMessages(string $permissionKey): string
    {
        return match ($permissionKey) {
            self::VIEW_SHOPS => __('You do not have permission to view shops.'),
            self::CREATE_SHOPS => __('You do not have permission to create shops.'),
            self::UPDATE_SHOPS => __('You do not have permission to update shops.'),
            self::DELETE_SHOPS => __('You do not have permission to delete shops.'),
            default => __('You do not have permission to perform this action.'),
        };
    }

    public function canViewShops()
    {
        if (!request()->user()->hasAnyPermission([self::VIEW_SHOPS])) {
            throw new \Exception($this->getErrorMessages(self::VIEW_SHOPS), 403);
        }
    }

    public function canCreateShops()
    {
        if (!request()->user()->hasAnyPermission([self::CREATE_SHOPS])) {
            throw new \Exception($this->getErrorMessages(self::CREATE_SHOPS), 403);
        }
    }

    public function canUpdateShops()
    {
        if (!request()->user()->hasAnyPermission([self::UPDATE_SHOPS])) {
            throw new \Exception($this->getErrorMessages(self::UPDATE_SHOPS), 403);
        }
    }

    public function canDeleteShops()
    {
        if (!request()->user()->hasAnyPermission([self::DELETE_SHOPS])) {
            throw new \Exception($this->getErrorMessages(self::DELETE_SHOPS), 403);
        }
    }
}
