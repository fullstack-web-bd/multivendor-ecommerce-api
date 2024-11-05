<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ProductException;
use App\Models\Product;
use App\Repositories\ProductImageRepository;
use App\Repositories\ProductRepository;
use App\Traits\Slugger;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    use Slugger;

    public const IMAGE_DIRECTORY = 'images/products';

    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductImageRepository $productImageRepository,
        private readonly FileUploadService $fileUploadService,
        private readonly MultipleFileUploadService $multipleFileUploadService,
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
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug(
                Product::class,
                $data['name']
            );
        }

        $product = $this->productRepository->store($data);

        if (!$product) {
            throw new ProductException('Product could not be created.');
        }

        $data = $this->uploadProductImages($data, $product);

        return $this->productRepository->show($product->id);
    }

    public function update(array $data, int $id): Product
    {
        $product = $this->productRepository->show($id);

        if (!$product) {
            throw new ProductException('Product not found.');
        }

        $data = $this->uploadProductImages($data, $product);

        if ($this->productRepository->update($data, $id)) {
            return $this->productRepository->show($id);
        }

        throw new ProductException('Product could not be updated.');
    }

    public function destroy(int $id): bool
    {
        $product = $this->productRepository->show($id);

        if (!$product) {
            throw new ProductException('Product not found.');
        }

        if ($product->images) {
            foreach ($product->images as $productImage) {
                $this->fileUploadService
                    ->setTargetLocation(self::IMAGE_DIRECTORY)
                    ->setFileName($productImage->file)
                    ->setFileNameAndExtensionFromFullFileName()
                    ->delete();

                $this->productImageRepository->destroy($productImage->id);
            }
        }

        return $this->productRepository->destroy($id);
    }

    public function destroyImage(int $id): bool
    {
        $productImage = $this->productImageRepository->show($id);

        if (!$productImage) {
            throw new ProductException('Product image not found.');
        }

        // Delete the image file from the server.
        $this->fileUploadService
            ->setTargetLocation(self::IMAGE_DIRECTORY)
            ->setFileName($productImage->file)
            ->setFileNameAndExtensionFromFullFileName()
            ->delete();

        return $this->productImageRepository->destroy($id);
    }

    private function uploadProductImages(array $data, Product $product): array
    {
        $requestImages = $data['images'];
        unset($data['images']);

        if (!empty($requestImages)) {
            $imageFileNames = $this->multipleFileUploadService
                ->setUploadedFiles($requestImages)
                ->setFileName($data['name'])
                ->setTargetLocation(self::IMAGE_DIRECTORY)
                ->setFileType('image')
                ->uploadMultiple();

            $productImages = [];
            foreach ($imageFileNames as $index => $fileName) {
                $productImages[] = [
                    'product_id' => $product->id,
                    'is_featured' => $index === 0 ? 1 : 0,
                    'file' => $fileName,
                    'order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Store the product images in the product_images table.
            $this->productImageRepository->storeMultiple($productImages);
        }

        return $data;
    }
}