<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\CategoryException;
use App\Models\Category;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Traits\ImageUploader;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    use ImageUploader;

    public const IMAGE_DIRECTORY = 'images/categories';

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly BrandRepository $brandRepository,
        private readonly FileUploadService $fileUploadService
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
        if (isset($data['image'])) {
            // $data['image'] = $this->upload(
            //     [
            //         'file' => $data['image'],
            //         'name' => $data['name'],
            //         'target_location' => self::IMAGE_DIRECTORY,
            //     ]
            // );
            $data['image'] = $this->fileUploadService
                ->setUploadedFile($data['image'])
                ->setTargetLocation(self::IMAGE_DIRECTORY)
                ->setFileType('image')
                ->setFileName($data['name'])
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

        if (isset($data['image'])) {
            // $data['image'] = $this->upload(
            //     [
            //         'file' => $data['image'],
            //         'name' => $category['image'] ?? $data['name'],
            //         'target_location' => self::IMAGE_DIRECTORY,
            //         'create' => false,
            //     ]
            // );
            $data['image'] = $this->fileUploadService
                ->setUploadedFile($data['image'])
                ->setTargetLocation(self::IMAGE_DIRECTORY)
                ->setFileType('image')
                ->setFileName($data['name'])
                ->update();
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

        $this->delete(self::IMAGE_DIRECTORY, $category->image);

        return $this->categoryRepository->destroy($id);
    }
}