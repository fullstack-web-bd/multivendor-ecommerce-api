<?php

namespace App\Permissions;

class CategoriesPermission
{
    public const VIEW_CATEGORIES = 'categories.view';
    public const CREATE_CATEGORIES = 'categories.create';
    public const UPDATE_CATEGORIES = 'categories.update';
    public const DELETE_CATEGORIES = 'categories.delete';

    protected function getErrorMessages(string $permissionKey): string
    {
        return match ($permissionKey) {
            self::VIEW_CATEGORIES => __('You do not have permission to view categories.'),
            self::CREATE_CATEGORIES => __('You do not have permission to create categories.'),
            self::UPDATE_CATEGORIES => __('You do not have permission to update categories.'),
            self::DELETE_CATEGORIES => __('You do not have permission to delete categories.'),
            default => __('You do not have permission to perform this action.'),
        };
    }

    public function canViewCategories()
    {
        if (!request()->user()->hasAnyPermission([self::VIEW_CATEGORIES])) {
            throw new \Exception($this->getErrorMessages(self::VIEW_CATEGORIES), 403);
        }
    }

    public function canCreateCategories()
    {
        if (!request()->user()->hasAnyPermission([self::CREATE_CATEGORIES])) {
            throw new \Exception($this->getErrorMessages(self::CREATE_CATEGORIES), 403);
        }
    }

    public function canUpdateCategories()
    {
        if (!request()->user()->hasAnyPermission([self::UPDATE_CATEGORIES])) {
            throw new \Exception($this->getErrorMessages(self::UPDATE_CATEGORIES), 403);
        }
    }

    public function canDeleteCategories()
    {
        if (!request()->user()->hasAnyPermission([self::DELETE_CATEGORIES])) {
            throw new \Exception($this->getErrorMessages(self::DELETE_CATEGORIES), 403);
        }
    }
}
