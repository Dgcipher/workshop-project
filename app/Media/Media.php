<?php
namespace App\Media;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Media{
    public static function Upload($image):string
    {
        $image->hashName();
        return $image->store('images');
    }
    public static function Delete($name):bool
    {
        return Storage::delete($name);
    }
}
