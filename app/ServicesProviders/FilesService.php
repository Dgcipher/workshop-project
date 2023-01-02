<?php
namespace App\ServicesProviders;
use App\InterfaceProviders\FilesInterFace;
use App\Media\Media;
use App\Models\Article;

class FilesService implements FilesInterFace {

    public function uploadFile($file, $dir) :string
    {
        return Media::Upload($dir,$file);
    }
    public function deleteFile($dir) :bool {
       return Media::Delete($dir);
    }
}
