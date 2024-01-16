<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\BrandException;
use App\Models\Brand;
use App\Repositories\BrandRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandService
{
    public const IMAGE_DIRECTORY = 'images/brands';

    public function __construct(
        private readonly BrandRepository $brandRepository,
        private readonly FileUploadService $fileUploadService,
    ) {
    }

    public function get(): LengthAwarePaginator
    {
        return $this->brandRepository->get();
    }

    public function findById(int $id): Brand
    {
        return $this->brandRepository->show($id);
    }

    public function findBySlug(string $slug): Brand
    {
        return $this->brandRepository->show($slug);
    }

    public function store(array $data): Brand
    {
        if ($data['image']) {
            $data['image'] = $this->fileUploadService
                ->setUploadedFile($data['image'])
                ->setFileName($data['name'])
                ->setTargetLocation(self::IMAGE_DIRECTORY)
                ->setFileType('image')
                ->upload();
        }
        return $this->brandRepository->store($data);
    }

    public function update(array $data, int $id): Brand
    {
        $brand = $this->brandRepository->show($id);

        if (!$brand) {
            throw new BrandException('Brand not found.');
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

        if ($this->brandRepository->update($data, $id)) {
            return $this->brandRepository->show($id);
        }

        throw new BrandException('Brand could not be updated.');
    }

    public function destroy(int $id): bool
    {
        $brand = $this->brandRepository->show($id);

        if (!$brand) {
            throw new BrandException('Brand not found.');
        }

        if ($brand->image) {
            $this->fileUploadService
                ->setTargetLocation(self::IMAGE_DIRECTORY)
                ->setFileName($brand->image)
                ->setFileNameAndExtensionFromFullFileName()
                ->delete();
        }

        return $this->brandRepository->destroy($id);
    }
}