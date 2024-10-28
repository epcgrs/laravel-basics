<?php

namespace Emmanuelpcg\Basics\ImageManipulation;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use InvalidArgumentException;

trait ImageManipulation
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Process and save an uploaded image
     *
     * @param mixed $requestFile The uploaded file from request
     * @param int $width Target width
     * @param int $height Target height
     * @param string $format Output format
     * @param int $quality Output quality
     * @param string|null $newName Custom filename (optional)
     * @param string $disk Storage disk name
     * @return string The saved file path
     * @throws InvalidArgumentException|Exception
     */
    public function resizeAndSaveImage(
        mixed $requestFile,
        int $width,
        int $height,
        string $format = 'jpg',
        int $quality = 90,
        ?string $newName = null,
        string $disk = 'public'
    ): string {
        
        if (!$requestFile instanceof UploadedFile) {
            throw new InvalidArgumentException("No valid file found in request");
        }

        
        $allowedFormats = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array(strtolower($format), $allowedFormats)) {
            throw new InvalidArgumentException("Invalid format. Allowed formats are: " . implode(', ', $allowedFormats));
        }

        
        if ($quality < 1 || $quality > 100) {
            throw new InvalidArgumentException("Quality must be between 1 and 100");
        }

        try {
            
            $image = $this->manager->read($requestFile);

            $image->scale(width: $width, height: $height);

            $filename = $this->generateUniqueFilename($requestFile, $newName, $format);
     
            $encodedImage = $image->toJpeg($quality);

            if (!Storage::disk($disk)->put($filename, $encodedImage)) {
                throw new Exception("Failed to save image to storage");
            }

            return $filename;

        } catch (Exception $e) {
            throw new Exception("Error processing image: " . $e->getMessage());
        }
    }

    /**
     * Generate a unique filename for the image
     */
    protected function generateUniqueFilename(UploadedFile $file, ?string $newName, string $format): string
    {
        $basename = $newName ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $basename = Str::slug($basename); // Use Laravel's Str helper

        return sprintf(
            'images/%s-%s.%s',
            $basename,
            uniqid(),
            $format
        );
    }
}