<?php
namespace app\Traits;
trait ApiRespone{
    public function Apirespone(bool $sucsess,string $message, $data,bool $error,int $statuscode){
        return response()->json(
            [
            'success'=>$sucsess,
            'message'=>$message,'data'=>$data ,
            'error'=>$error
            ], $statuscode);
    }
    public function success(string $message,int $statuscode =200){
        return $this->Apirespone(true,$message,[],false,$statuscode);
    }
    public function error(string $message,int $statuscode){
        return $this->Apirespone(false,$message,[],true,$statuscode);
    }
    public function SendData(string $message, $data,int $statuscode=200){
        return $this->Apirespone(true,$message,$data,false,$statuscode);
    }

}
