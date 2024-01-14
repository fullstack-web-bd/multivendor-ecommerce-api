<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

class FileUploadService
{
    private UploadedFile $file;
    private string $fileType;
    private string $targetLocation;
    private string $fileName;
    private string $fileExtension;
    private int $fileWidth;
    private int $fileHeight;
    private array $validMimeTypesByFileType = [
        'image' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml', 'image/bmp', 'image/jpg']
    ];

    public function getUploadedFile(): UploadedFile
    {
        return $this->file;
    }

    public function setUploadedFile(UploadedFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getTargetLocation(): string
    {
        return $this->targetLocation;
    }

    public function setTargetLocation(string $targetLocation): self
    {
        $this->targetLocation = $targetLocation;

        return $this;
    }

    public function getFileType(): string
    {
        return $this->fileType;
    }

    public function setFileType(string $fileType): self
    {
        $this->fileType = $fileType;

        return $this;
    }

    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    public function setFileExtension(string $fileExtension): self
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileWidth(): int
    {
        return $this->fileWidth;
    }

    public function setFileWidth(int $fileWidth): self
    {
        $this->fileWidth = $fileWidth;

        return $this;
    }

    public function getFileHeight(): int
    {
        return $this->fileHeight;
    }

    public function setFileHeight(int $fileHeight): self
    {
        $this->fileHeight = $fileHeight;

        return $this;
    }

    public function maybeCreateDirectory(): self
    {
        if (!file_exists(storage_path('app/public/' . $this->targetLocation))) {
            mkdir(storage_path('app/public/' . $this->targetLocation), 0777, true);
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function upload(): string
    {
        $this->maybeCreateDirectory();
        if (!$this->validate()) {
            throw new \Exception('Invalid file.');
        }

        switch ($this->fileType) {
            case 'image':
                $this->uploadImage();
                break;
            default:
                throw new \Exception('Invalid file type.');
        }

        return $this->fileName . '.' . $this->fileExtension;
    }

    /**
     * @throws \Exception
     */
    public function update(): string
    {
        if (!$this->validate()) {
            throw new \Exception('Invalid file.');
        }

        $this->maybeCreateDirectory()
            ->updateFileNameAndExtensionFromFullFilePath()
            ->delete();

        switch ($this->fileType) {
            case 'image':
                $this->uploadImage();
                break;
            default:
                throw new \Exception('Invalid file type.');
        }

        return $this->fileName . '.' . $this->fileExtension;
    }

    public function validate(): bool
    {
        switch ($this->fileType) {
            case 'image':
                return $this->validateImage();
            default:
                throw new \Exception('Invalid file type.');
        }
    }

    private function validateImage(): bool
    {
        if (!$this->file->isValid()) {
            throw new \Exception('Invalid file.');
        }

        if (
            !in_array(
                $this->file->getMimeType(),
                $this->validMimeTypesByFileType[$this->fileType] ?? []
            )
        ) {
            throw new \Exception('Invalid file type.');
        }

        return true;
    }

    public function getFileAsImage(): ImageInterface
    {
        $manager = ImageManager::gd();
        return $manager->read($this->file->getRealPath());
    }

    private function uploadImage(): ImageInterface
    {
        $image = $this
            ->setFileExtension('webp')
            ->getFileAsImage();

        // If resizer is set, resize the image.
        if (isset($this->fileWidth) && isset($this->fileHeight)) {
            $image->resize($this->fileWidth, $this->fileHeight);
        }

        return $image->save($this->getFileStoragePath(), quality: 100);
    }

    public function delete(): bool
    {
        try {
            // Check if file exists.
            if (!file_exists($this->getFileStoragePath())) {
                return false;
            }

            return unlink($this->getFileStoragePath());
        } catch (\Throwable $th) {
            Log::error('Error deleting file');
            Log::error($th);

            return false;
        }
    }

    private function getFileStoragePath(): string
    {
        return storage_path('app/public/' . $this->targetLocation . '/' . $this->fileName . '.' . $this->fileExtension);
    }

    private function updateFileNameAndExtensionFromFullFilePath(): self
    {
        $this->fileName = pathinfo($this->fileName, PATHINFO_FILENAME);
        $this->fileExtension = pathinfo($this->fileName, PATHINFO_EXTENSION);

        return $this;
    }
}