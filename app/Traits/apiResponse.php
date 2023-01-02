<?php

namespace App\Traits;

trait apiResponse
{
    public function ApiResponse($data=null,$msg=null,$status=null){
        $data=[
        'data'=>$data,
        'msg'=>$msg,
        'status'=>$status
        ];

        return response($data,$status);
    }

}
