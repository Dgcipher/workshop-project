<?php
namespace App\InterfaceProviders;
interface FilesInterface{
    public function uploadFile($file);
    public function deleteFile($name);
}
