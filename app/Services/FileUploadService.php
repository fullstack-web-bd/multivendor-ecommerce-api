<?php

declare(strict_types=1);

namespace App\Services;
use App\Exceptions\FileHandlerException;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        'image' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml', 'image/bmp', 'image/jpg'],
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

    public function upload(): string
    {
        $this->validate()
            ->maybeCreateDirectory();

        switch ($this->fileType) {
            case 'image':
                $this->uploadImage();
                break;

            default:
                # code...
                break;
        }

        return $this->getFormattedFileName();
    }

    public function update()
    {
        $this->validate()
            ->maybeCreateDirectory()
            ->setFileNameAndExtensionFromFullFileName()
            ->delete();

        switch ($this->fileType) {
            case 'image':
                $this->uploadImage();
                break;

            default:
                # code...
                break;
        }

        return $this->getFormattedFileName();
    }

    public function delete(): bool
    {
        try {
            $previousStoragePath = storage_path('app/public/' . $this->targetLocation . '/' . $this->fileName . '.' . $this->fileExtension);

            if (!file_exists($previousStoragePath)) {
                return false;
            }

            return unlink($previousStoragePath);
        } catch (\Throwable $th) {
            Log::error('Error deleting file');
            Log::error($th);

            return false;
        }
    }

    private function uploadImage(): ImageInterface
    {
        $image = $this->setFileExtension('webp')
            ->getFileAsImage();

        return $image->save($this->getFileStoragePath());
    }

    private function getFileAsImage(): ImageInterface
    {
        $manager = ImageManager::gd();
        return $manager->read($this->file->getRealPath());
    }

    private function getFileStoragePath(): string
    {
        return storage_path('app/public/' . $this->targetLocation . '/' . $this->getFormattedFileName());
    }

    private function getFormattedFileName(): string
    {
        return substr(Str::slug($this->fileName . '-' . time()), 0, 50) . '.' . $this->fileExtension;
    }

    public function maybeCreateDirectory(): self
    {
        if (!file_exists(storage_path('app/public/' . $this->targetLocation))) {
            mkdir(storage_path('app/public/' . $this->targetLocation), 0777, true);
        }

        return $this;
    }

    public function validate(): self
    {
        switch ($this->fileType) {
            case 'image':
                return $this->validateImage();
            default:
                throw new FileHandlerException('Invalid file type.');
        }
    }

    public function setFileNameAndExtensionFromFullFileName(): self
    {
        $this->fileExtension = pathinfo($this->fileName, PATHINFO_EXTENSION);
        $this->fileName = pathinfo($this->fileName, PATHINFO_FILENAME);

        return $this;
    }

    private function validateImage(): self
    {
        if (!$this->file->isValid()) {
            throw new FileHandlerException('Invalid file.');
        }

        if (
            !in_array(
                $this->file->getMimeType(),
                $this->validMimeTypesByFileType[$this->fileType] ?? []
            )
        ) {
            throw new FileHandlerException('Invalid file type.');
        }

        return $this;
    }
}
