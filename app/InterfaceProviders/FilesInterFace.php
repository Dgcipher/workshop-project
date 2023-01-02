<?php
namespace App\InterfaceProviders;
interface FilesInterFace{
    public function uploadFile($file,$dir);
    public function deleteFile($dir);
}
