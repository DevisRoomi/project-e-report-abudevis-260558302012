<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class StoredImage
{
    public static function delete(?string $path, string $directory): void
    {
        if ($path && preg_match('#^'.preg_quote($directory, '#').'/[a-zA-Z0-9_\-\.]+\.(jpg|jpeg|png|webp)$#i', $path)) {
            Storage::disk('public')->delete($path);
        }
    }
}