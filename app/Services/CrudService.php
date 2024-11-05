<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ProductException;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class CrudService
{
    public const IMAGE_DIRECTORY = 'images/products';

    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly FileUploadService $fileUploadService,
    ) {
    }

    public function get(): LengthAwarePaginator
    {
        return $this->productRepository->get();
    }

    public function findById(int $id): Product
    {
        return $this->productRepository->show($id);
    }

    public function findBySlug(string $slug): Product
    {
        return $this->productRepository->show($slug);
    }

    public function store(array $data): Product
    {
        if ($data['image']) {
            $data['image'] = $this->fileUploadService
                ->setUploadedFile($data['image'])
                ->setFileName($data['name'])
                ->setTargetLocation(self::IMAGE_DIRECTORY)
                ->setFileType('image')
                ->upload();
        }
        return $this->productRepository->store($data);
    }

    public function update(array $data, int $id): Product
    {
        $brand = $this->productRepository->show($id);

        if (!$brand) {
            throw new ProductException('Product not found.');
        }

        if ($data['image']) {
            $data['image'] = $this->fileUploadService
                ->setUploadedFile($data['image'])
                ->setFileName($data['name'])
                ->setTargetLocation(self::IMAGE_DIRECTORY)
                ->setFileType('image')
                ->update();
        } else {
            $data['image'] = $brand->image;
        }

        if ($this->productRepository->update($data, $id)) {
            return $this->productRepository->show($id);
        }

        throw new ProductException('Product could not be updated.');
    }

    public function destroy(int $id): bool
    {
        $brand = $this->productRepository->show($id);

        if (!$brand) {
            throw new ProductException('Product not found.');
        }

        if ($brand->image) {
            $this->fileUploadService
                ->setTargetLocation(self::IMAGE_DIRECTORY)
                ->setFileName($brand->image)
                ->setFileNameAndExtensionFromFullFileName()
                ->delete();
        }

        return $this->productRepository->destroy($id);
    }
}