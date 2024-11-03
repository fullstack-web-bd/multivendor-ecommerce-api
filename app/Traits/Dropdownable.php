<?php

declare(strict_types=1);

namespace App\Traits;

trait Dropdownable
{
    /**
     * Generate key-value pairs for dropdown.
     *
     * @param string $keyColumn
     * @param string $valueColumn
     * @return array
     */
    public static function dropdown(
        string $keyColumn,
        string $valueColumn
    ): array {
        return self::pluck($valueColumn, $keyColumn)->toArray();
    }
}