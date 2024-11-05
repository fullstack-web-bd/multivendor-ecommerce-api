<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ModelCrudInterface;
use App\Models\ProductImages;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductImageRepository implements ModelCrudInterface
{
    public function get(): LengthAwarePaginator
    {
        return ProductImages::paginate(20);
    }

    public function show(string|int $id): ProductImages
    {
        return ProductImages::findOrFail((int) $id);
    }

    public function store(array $data): ProductImages
    {
        return ProductImages::create($data);
    }

    public function update(array $data, string|int $idOrSlug): bool
    {
        $product = $this->show($idOrSlug);

        return $product->update($data);
    }

    public function destroy(string|int $idOrSlug): bool
    {
        $product = $this->show($idOrSlug);

        return $product->delete();
    }

    public function storeMultiple(array $data): void
    {
        ProductImages::insert($data);
    }
}