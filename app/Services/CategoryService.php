<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\CategoryException;
use App\Models\Category;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    public const IMAGE_DIRECTORY = 'images/categories';

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly BrandRepository $brandRepository,
        private readonly FileUploadService $fileUploadService,
    ) {
    }

    public function get(): LengthAwarePaginator
    {
        return $this->categoryRepository->get();
    }

    public function dropdown(): array
    {
        return Category::dropdown('id', 'name');
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
        if ($data['image']) {
            $data['image'] = $this->fileUploadService
                ->setUploadedFile($data['image'])
                ->setFileName($data['name'])
                ->setTargetLocation(self::IMAGE_DIRECTORY)
                ->setFileType('image')
                ->upload();
        }
        return $this->categoryRepository->store($data);
    }

    public function update(array $data, int $id): Category
    {
        $category = $this->categoryRepository->show($id);

        if (!$category) {
            throw new CategoryException('Category not found.');
        }

        if ($data['image']) {
            $data['image'] = $this->fileUploadService
                ->setUploadedFile($data['image'])
                ->setFileName($data['name'])
                ->setTargetLocation(self::IMAGE_DIRECTORY)
                ->setFileType('image')
                ->update();
        } else {
            $data['image'] = $category->image;
        }

        if ($this->categoryRepository->update($data, $id)) {
            return $this->categoryRepository->show($id);
        }

        throw new CategoryException('Category could not be updated.');
    }

    public function destroy(int $id): bool
    {
        $category = $this->categoryRepository->show($id);

        if (!$category) {
            throw new CategoryException('Category not found.');
        }

        if ($category->image) {
            $this->fileUploadService
                ->setTargetLocation(self::IMAGE_DIRECTORY)
                ->setFileName($category->image)
                ->setFileNameAndExtensionFromFullFileName()
                ->delete();
        }

        return $this->categoryRepository->destroy($id);
    }
}