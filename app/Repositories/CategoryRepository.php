<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ModelCrudInterface;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements ModelCrudInterface
{
    public function get(): LengthAwarePaginator
    {
        return Category::paginate(20);
    }

    public function show(string|int $idOrSlug): Category
    {
        if (is_numeric($idOrSlug)) {
            return Category::findOrFail((int) $idOrSlug);
        }

        return Category::where('slug', $idOrSlug)->firstOrFail();
    }

    public function store(array $data): Category
    {
        return Category::create($data);
    }

    public function update(array $data, string|int $idOrSlug): bool
    {
        $category = $this->show($idOrSlug);

        return $category->update($data);
    }

    public function destroy(string|int $idOrSlug): bool
    {
        $category = $this->show($idOrSlug);

        return $category->delete();
    }
}