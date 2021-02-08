<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FilesHelper
{
    public static function getStorageDisk(string $disk = null)
    {
        if (!$disk) {
            $disk = config('filesystem.default');
        }

        $storage = Storage::disk($disk);

        return $storage;
    }
}
