<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageUploadService
{
    /**
     * Create a new class instance.
     */
    public function uploadPublic(UploadedFile $file,$path = 'all') : string
    {
        $path = $file->storePublicly($path,'public');
        return $path;
    }
}
