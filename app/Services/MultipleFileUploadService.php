<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\FileHandlerException;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MultipleFileUploadService
{
    private array $files = [];
    private $file;
    private string $fileType;
    private string $targetLocation;
    private string $fileName;
    private string $fileExtension;
    private array $validMimeTypesByFileType = [
        'image' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml', 'image/bmp', 'image/jpg'],
    ];

    public function getUploadedFiles(): array
    {
        return $this->files;
    }

    public function setUploadedFiles(array $files): self
    {
        $this->files = $files;
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

    /**
     * Handles uploading multiple files.
     * 
     * @return array List of file names after successful upload
     */
    public function uploadMultiple(): array
    {
        $this->validateFiles() // Validate all files at once
            ->maybeCreateDirectory();

        $uploadedFiles = [];

        foreach ($this->files as $file) {
            // Process each file individually
            $this->setUploadedFile($file); // Set each file for individual upload
            $uploadedFiles[] = $this->upload(); // Upload each file and add its formatted name to the result
        }

        return $uploadedFiles;
    }

    /**
     * Handles single file upload. Called within `uploadMultiple` for each file.
     * 
     * @return string The uploaded file's formatted name
     */
    public function upload(): string
    {
        $this->validateFile() // Validate the current file
            ->maybeCreateDirectory();

        switch ($this->fileType) {
            case 'image':
                $this->uploadImage(); // Handle image upload
                break;
            default:
                break;
        }

        return $this->getFormattedFileName(); // Return the formatted name of the uploaded file
    }

    /**
     * Uploads an image to the target location.
     * 
     * @return ImageInterface The saved image object
     */
    private function uploadImage(): ImageInterface
    {
        $image = $this->setFileExtension('webp') // Set the file extension to "webp"
            ->getFileAsImage(); // Convert the file to an image

        return $image->save($this->getFileStoragePath()); // Save the image to the storage path
    }

    /**
     * Returns the file as an image object.
     * 
     * @return ImageInterface
     */
    private function getFileAsImage(): ImageInterface
    {
        $manager = ImageManager::gd();
        return $manager->read($this->file->getRealPath()); // Read the current file as an image
    }

    /**
     * Returns the storage path for the file.
     * 
     * @return string
     */
    private function getFileStoragePath(): string
    {
        return storage_path('app/public/' . $this->targetLocation . '/' . $this->getFormattedFileName()); // Path to where the file will be saved
    }

    /**
     * Generates a formatted file name.
     * 
     * @return string The formatted file name with extension
     */
    private function getFormattedFileName(): string
    {
        return substr(Str::slug($this->fileName . '-' . time()), 0, 50) . '.' . $this->fileExtension; // Format the file name
    }

    /**
     * Ensures the target directory exists.
     * 
     * @return self
     */
    public function maybeCreateDirectory(): self
    {
        if (!file_exists(storage_path('app/public/' . $this->targetLocation))) {
            mkdir(storage_path('app/public/' . $this->targetLocation), 0777, true); // Create the directory if it doesn't exist
        }

        return $this;
    }

    /**
     * Validates the files before upload.
     * 
     * @return self
     * 
     * @throws FileHandlerException
     */
    public function validateFiles(): self
    {
        foreach ($this->files as $file) {
            $this->setUploadedFile($file);  // Set each file for individual validation
            $this->validateFile();  // Validate each file
        }

        return $this;
    }

    /**
     * Validates a single file.
     * 
     * @return self
     * 
     * @throws FileHandlerException
     */
    public function validateFile(): self
    {
        if (!$this->file->isValid()) { // Validate the current file
            throw new FileHandlerException('One or more files are invalid.');
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

    /**
     * Sets the uploaded file for individual validation.
     * 
     * @param UploadedFile $file
     * @return self
     */
    public function setUploadedFile(UploadedFile $file): self
    {
        $this->file = $file; // Set the current file for upload/validation
        return $this;
    }
}
