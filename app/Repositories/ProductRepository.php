<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ModelCrudInterface;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ModelCrudInterface
{
    public const WITH_RELATIONSHIPS = ['brand', 'category', 'shop', 'images', 'featuredImage'];

    public function get(): LengthAwarePaginator
    {
        return Product::with(self::WITH_RELATIONSHIPS)->paginate(20);
    }

    public function show(string|int $idOrSlug): Product
    {
        if (is_numeric($idOrSlug)) {
            return Product::with(self::WITH_RELATIONSHIPS)->findOrFail((int) $idOrSlug);
        }

        return Product::with(self::WITH_RELATIONSHIPS)->where('slug', $idOrSlug)->firstOrFail();
    }

    public function store(array $data): Product
    {
        $product = Product::create($data);
        $product->load(self::WITH_RELATIONSHIPS);

        return $product;
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
}