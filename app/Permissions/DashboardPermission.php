<?php

namespace App\Permissions;

class DashboardPermission
{
    public const VIEW_DASHBOARD = 'dashboard.view';
    public const VIEW_DASHBOARD_ADVANCE_REPORT = 'dashboard.advanced_report';

    protected function getErrorMessages(string $permissionKey): string
    {
        return match ($permissionKey) {
            self::VIEW_DASHBOARD => __('You do not have permission to view dashboard.'),
            self::VIEW_DASHBOARD_ADVANCE_REPORT => __('You do not have permission to view advance report.'),
            default => __('You do not have permission to perform this action.'),
        };
    }

    public function canViewDashboard()
    {
        if (!request()->user()->hasAnyPermission([self::VIEW_DASHBOARD])) {
            throw new \Exception($this->getErrorMessages(self::VIEW_DASHBOARD), 403);
        }
    }

    public function canViewAdvanceReport()
    {
        if (!request()->user()->hasAnyPermission([self::VIEW_DASHBOARD_ADVANCE_REPORT])) {
            throw new \Exception($this->getErrorMessages(self::VIEW_DASHBOARD_ADVANCE_REPORT), 403);
        }
    }
}
