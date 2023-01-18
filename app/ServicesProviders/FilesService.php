<?php
namespace App\ServicesProviders;
use App\InterfaceProviders\FilesInterface;
use App\Media\Media;
use App\Models\Article;

class FilesService {

    public function uploadFile($file) :string
    {
        return Media::Upload($file);
    }
    public function deleteFile($dir) :bool
    {
       return Media::Delete($dir);
    }
}
