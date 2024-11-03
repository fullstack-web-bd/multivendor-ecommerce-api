<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

trait Slugger
{
    public function generateSlug(
        string $modelName,
        string $columnValue,
        string $slugColumn = 'slug'
    ): string {
        $slug = Str::slug($columnValue);
        $originalSlug = $slug;
        $count = 1;

        // Check for uniqueness and append a counter if needed
        while ($modelName::where($slugColumn, $slug)->exists()) {
            $slug = "{$originalSlug}-" . $count;
            $count++;
        }

        return $slug;
    }
}
