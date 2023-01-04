<?php

namespace App\Traits;

use phpseclib3\Crypt\Hash;
use Symfony\Component\HttpFoundation\Request;

trait UploadImageTrait
{
    public function uploadeimage(Request $request,$foldername){
        //        hashName(),storeAs,move

        $image =$request->file('path');
        $path =$image->storeAs($foldername,$image->getClientOriginalName(),'public');
        return $path;
    }
}
