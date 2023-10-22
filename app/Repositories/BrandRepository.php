<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ModelCrudInterface;
use App\Models\Brand;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandRepository implements ModelCrudInterface
{
    public function get(): LengthAwarePaginator
    {
        return Brand::paginate(20);
    }

    public function show(string|int $idOrSlug): Brand
    {
        if (is_numeric($idOrSlug)) {
            return Brand::findOrFail((int) $idOrSlug);
        }

        return Brand::where('slug', $idOrSlug)->firstOrFail();
    }

    public function store(array $data): Brand
    {
        return Brand::create($data);
    }

    public function update(array $data, string|int $idOrSlug): bool
    {
        $brand = $this->show($idOrSlug);

        return $brand->update($data);
    }

    public function destroy(string|int $idOrSlug): bool
    {
        $brand = $this->show($idOrSlug);

        return $brand->delete();
    }
}