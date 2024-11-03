<?php

declare(strict_types=1);

namespace App\Repositories;
use App\Interfaces\ModelCrudInterface;
use App\Models\Shop;
use Illuminate\Pagination\LengthAwarePaginator;

class ShopRepository implements ModelCrudInterface
{
    public function get(): LengthAwarePaginator
    {
        return Shop::paginate(20);
    }

    public function show(string|int $idOrSlug): Shop
    {
        if (is_numeric($idOrSlug)) {
            return Shop::findOrFail((int) $idOrSlug);
        }

        return Shop::where('slug', $idOrSlug)->firstOrFail();
    }

    public function store(array $data): Shop
    {
        return Shop::create($data);
    }

    public function update(array $data, string|int $idOrSlug): bool
    {
        $shop = $this->show($idOrSlug);

        return $shop->update($data);
    }

    public function destroy(string|int $idOrSlug): bool
    {
        $shop = $this->show($idOrSlug);

        return $shop->delete();
    }
}