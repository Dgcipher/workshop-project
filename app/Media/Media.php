<?php
namespace App\Media;

use Illuminate\Support\Facades\File;

class Media{
    public static function Upload(string $dir,$image):string{
        $newImageName = $image->hashName();
        $image->move($dir,$newImageName);
        return $newImageName;
    }
    public static function Delete(string $dir):bool{
        if(File::exists($dir)){
            File::delete($dir);
            return true;
        }
        return false;
    }
}
