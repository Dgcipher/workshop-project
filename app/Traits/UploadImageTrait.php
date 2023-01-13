<?php

namespace App\Traits;

use phpseclib3\Crypt\Hash;
use Symfony\Component\HttpFoundation\Request;

trait UploadImageTrait
{
    public function uploadeimage(Request $request,$foldername){
        //        hashName(),storeAs,move

        $image =$request->file('path');
        $path =$image->move($foldername,$image->getClientOriginalName(),'public');
        return $path;
    }
    public function deleteimage(Request $request,$foldername){
        $image =$request->file('path');
        $path =$image->deleteDirectory('public');
        return $path;
    }
}
