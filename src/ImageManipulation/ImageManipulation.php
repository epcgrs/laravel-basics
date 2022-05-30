<?php

namespace Emmanuelpcg\Basics\ImageManipulation;

use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

abstract class ImageManipulation
{
    /**
     * @throws Exception
     */
    protected function resizeAndSaveImage(string $requestFile = 'image', string $disk = 'public', int $width = 1280, int $height = 720, string $newName = null): string
    {
        if (is_null(request()->file($requestFile))) {
            throw new Exception("File not exists in request.");
        }

        $image = request()->file($requestFile);
        $imageStream = Image::make($image)->resize($width, $height)->stream('webp', 85);
        $imageName = '/image/' . ($newName ?: $image->getClientOriginalName()) . '-' . time() . '.webp';

        if(Storage::disk($disk)->put($imageName, $imageStream)) {
            return $imageName;
        }

        throw new Exception("Error while save image.");
    }

}