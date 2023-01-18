<?php
namespace App\InterfaceProviders;
interface FilesInterface{
    public function uploadFile($file,$dir);
    public function deleteFile($dir);
}
