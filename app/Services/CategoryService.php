<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly BrandRepository $brandRepository
    ) {
    }

    public function get(): LengthAwarePaginator
    {
        return $this->categoryRepository->get();
    }

    public function findById(int $id): Category
    {
        return $this->categoryRepository->show($id);
    }

    public function findBySlug(string $slug): Category
    {
        return $this->categoryRepository->show($slug);
    }

    public function store(array $data): Category
    {
        return $this->categoryRepository->store($data);
    }

    public function update(array $data, int $id): bool
    {
        return $this->categoryRepository->update($data, $id);
    }

    public function destroy(int $id): bool
    {
        return $this->categoryRepository->destroy($id);
    }
}