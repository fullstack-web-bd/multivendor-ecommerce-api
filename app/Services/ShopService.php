<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Shop;
use App\Repositories\AuthRepository;
use App\Repositories\ShopRepository;
use App\Traits\Slugger;

class ShopService
{
    use Slugger;

    public function __construct(
        private readonly AuthRepository $authRepository,
        private readonly ShopRepository $shopRepository,
    ) {
    }

    public function createShop(array $data): Shop
    {
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug(
                Shop::class,
                $data['name']
            );
        }

        return $this->shopRepository->store($data);
    }

    public function dropdown(): array
    {
        return Shop::dropdown('id', 'name');
    }
}