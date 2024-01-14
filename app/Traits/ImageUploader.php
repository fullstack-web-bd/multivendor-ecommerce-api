<?php

declare(strict_types=1);

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

trait ImageUploader
{
    /**
     * Upload or update an image.
     *
     * @param array $args
     * @return string
     */
    public function upload(array $args = []): string
    {
        $defaultArgs = [
            'file' => null,
            'target_location' => null, // Where to upload the file.
            'create' => true, // create or update.
            'name' => null, // name of the image to be uploaded.
            'width' => null, // width of the image.
            'height' => null, // height of the image.
            'webp' => true // convert image to webp.
        ];

        try {
            $args = array_merge($defaultArgs, $args);

            // Handle mandatory arguments.
            if (!$args['target_location']) {
                throw new Exception('Target location is required.');
            }

            if (!$args['file'] && $args['create'] ?? true) {
                throw new Exception('File is required.');
            }

            // If empty name, generate a random name.
            if (!$args['name']) {
                $args['name'] = uniqid();
            }

            // If empty file, return name.
            if (empty($args['file']) || !($args['file'] instanceof UploadedFile)) {
                return $args['name'] ?? '';
            }

            // For Edit, if the image is not changed, return the old image name.
            if (!$args['create'] && !$args['file']) {
                return $args['name'] ?? '';
            }

            // If create is false, means it's for edit.
            // So, if the image is changed, delete the old image.
            if (!$args['create'] && $args['file']) {
                $this->delete($args['target_location'] ?? '', $args['name']);
            }

            // Truncate the file name to 50 characters and slugify it.
            $args['name'] = substr(Str::slug($args['name']), 0, 50) . '-' . uniqid();

            $fileName = $args['name'] . '.' . $args['file']->getClientOriginalExtension();

            // Check if configuration has width, height and if not, use the original image width and height
            $width = $args['width'] ?? $this->getImageWidth($args['file']);
            $height = $args['height'] ?? $this->getImageHeight($args['file']);

            $manager = ImageManager::gd();
            $image = $manager->read($args['file']->getRealPath());

            // Resize image if width and height are set.
            if ($width && $height) {
                $image->scale(
                    width: $width,
                    height: $height
                );
            }

            // Convert image to webp if webp is set to true.
            $fileLocation = storage_path('app/public/' . $args['target_location']);

            if ($args['webp'] && $args['create']) {
                $fileName = $args['name'] . '.webp';
            } else if ($args['webp'] && !$args['create']) {
                $fileName = $args['name']; // Don't add extension if it's for edit.
            }

            // Copy the original image to the target location. (If someone needs)
            // $image->save($fileLocation . '/' . $args['name'] . '.' . $args['file']->getClientOriginalExtension(), quality: 100);

            // Copy the webp image to the target location.
            $image->save($fileLocation . '/' . $fileName, quality: 100);

            Log::info($fileName . ' uploaded successfully');

            return $fileName;
        } catch (\Throwable $th) {
            // Check if error is Intervention\Image\Exceptions\NotWritableException.
            // If so, create the directory and try again.
            if ($th instanceof \Intervention\Image\Exceptions\NotWritableException) {
                $targetLocation = storage_path('app/public/' . $args['target_location']);
                if (!file_exists($targetLocation)) {
                    mkdir($targetLocation, 0777, true);
                }
                return $this->upload($args);
            }

            Log::error('Error uploading image');
            Log::error($th);
            throw $th;
        }
    }

    private function getImageWidth($file): ?int
    {
        return getimagesize($file->getRealPath())[0] ?? null;
    }

    private function getImageHeight($file): ?int
    {
        return getimagesize($file->getRealPath())[1] ?? null;
    }

    public function delete(string $targetLocation, string $fileName): bool
    {
        try {
            $fileLocation = storage_path('app/public/' . $targetLocation);

            // If has the original image, delete it.
            // if (file_exists($fileLocation . '/' . $fileName)) {
            //     unlink($fileLocation . '/' . $fileName);
            // }

            if (file_exists($fileLocation . '/' . $fileName . '.webp')) {
                unlink($fileLocation . '/' . $fileName . '.webp');
            }

            Log::info($fileName . ' deleted successfully');

            return true;
        } catch (\Throwable $th) {
            Log::error('Error deleting image');
            Log::error($th);
            throw $th;
        }
    }
}
