<?php

namespace App\Http\Controllers;
use App\Http\Requests\blogRequest;
use App\Models\blog;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class BlogController extends Controller
{
    public function index():JsonResponse
    {
        $blogs=blog::all();
        return response()->json([
            'status' => 'Success',
            'status_code'=>ResponseAlias::HTTP_CREATED,
            'message'=>'',
            'data' => $blogs
        ], ResponseAlias::HTTP_CREATED);
    }

    public function show($id):JsonResponse
    {
        $blog = blog::find($id);
        return response()->json(['$data'=>$blog,'msg'=>'blog found','status'=>201]);
    }

    public function create()
    {
    }

    public function store(blogRequest $request):JsonResponse
    {
         $blog = blog::create($request->all());
        if ($blog) {
            return response()->json([
                'status' => 'Success',
                'status_code'=>ResponseAlias::HTTP_CREATED,
                'message'=>'',
                'data' => $blog
            ], ResponseAlias::HTTP_CREATED);
            }
            else{
                return response()->json([
                    'status' => 401,
                    'status_code'=>ResponseAlias::HTTP_CREATED,
                    'message'=>validator()->errors(),
                    'data' => $blog
                ], ResponseAlias::HTTP_CREATED);
            }

    }
    public function edit(blog $blog)
    {
        //
    }
    public function update(blogRequest $request, $id):JsonResponse
    {
        $blog = blog::find($id);
        if(!$blog){
            return $this->response()->json([
                'status' => 401,
                'status_code'=>ResponseAlias::HTTP_CREATED,
                'message'=>'Blog NOT FOUND'
            ], ResponseAlias::HTTP_CREATED);

        }else{
           $data= $blog->update($request->all());
            return response()->json([
                'status' => 201,
                'status_code'=>ResponseAlias::HTTP_CREATED,
                'message'=>'Blog Updated Successfully',
                'data' => $data
            ], ResponseAlias::HTTP_CREATED);
         }
    }

    public function destroy($id): JsonResponse
    {
        $blog = blog::find($id);
        if (!$blog) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'blog not found',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($blog->delete()) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_OK;
            $message = 'blog deleted successfully';
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'blog not deleted';
        }

        return response()->json([
            'status' => $status,
            'status_code'=>$status_code,
            'message' => $message,
            'data'=>[]
        ], $status_code);
    }
}
