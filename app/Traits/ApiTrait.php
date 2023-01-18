<?Php
namespace App\Http\Controllers;
trait ApiTrait{
public function ApiResponse($data=null,$msg=null,$status=null){

    $array = [
        'data'=>$data,
        'msg'=>$msg,
        'status'=>$status,
        ];
        return response($array,$status);
}


}






















?>























