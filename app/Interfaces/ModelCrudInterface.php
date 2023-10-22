<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface ModelCrudInterface
{
    public function get(): LengthAwarePaginator;

    public function show(string|int $idOrSlug): Model;

    public function store(array $data): Model;

    public function update(array $data, string|int $idOrSlug): bool;

    public function destroy(string|int $idOrSlug): bool;
}