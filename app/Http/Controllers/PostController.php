<?php

namespace App\Http\Controllers;



use App\Http\Requests\CreatePostsRequest;
use App\Http\Requests\SearchPostsRequest;
use App\Http\Requests\UpdatePostsRequest;
use App\Models\Post;
use App\Traits\UploadImageTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;



class PostController extends Controller
{

use UploadImageTrait;


    /**
     * Display a listing of the resource.
     *
     * @param SearchPostsRequest $request
     * @return JsonResponse
     */
    public function search(SearchPostsRequest $request): JsonResponse
    {
        $select = $request->input('select', ['*']);
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $data = Post::simplePaginate($per_page, $select, 'page', $page);

        return response()->json([
            'status' => 'Success',
            'status_code'=>ResponseAlias::HTTP_CREATED,
            'message'=>'',
            'data' => $data
        ], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePostsRequest $request
     * @return JsonResponse
     */
    public function create(CreatePostsRequest $request): JsonResponse
    {
        $path= $this->uploadeimage($request,'users');
        $possts=  Post::create([
            'title'=>$request->title,
            'body'=>$request->body,
            'path'=>$path,
        ]);
        if ($possts) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_CREATED;
            $message = 'Post created successfully';
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Post not created';
        }

        return response()->json([
            'status' => $status,
            'status_code'=>$status_code,
            'message' => $message,
            'data'=>[]
        ], $status_code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function read($id): JsonResponse
    {
        $user = Post::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Post not found','data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => 'Success',
            'status_code'=>ResponseAlias::HTTP_OK,
            'message' => '',
            'data'=>$user->only(['id','body','title'])
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePostsRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdatePostsRequest $request, $id): JsonResponse
    {
        $user = Post::findorfail($id);
        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Post not found',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
        $path= $this->uploadeimage($request,'users');
        $possts=$user->update([
            'title'=>$request->title,
            'body'=>$request->body,
            'path'=>$path,
        ]);
        if ($possts) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_OK;
            $message = 'Post updated successfully';
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Post not updated';
        }

        return response()->json([
            'status' => $status,
            'status_code'=>$status_code,
            'message' => $message,
            'data'=>[]
        ], $status_code);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $user = Post::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Post not found',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($user->delete()) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_OK;
            $message = 'Post deleted successfully';
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Post not deleted';
        }

        return response()->json([
            'status' => $status,
            'status_code'=>$status_code,
            'message' => $message,
            'data'=>[]
        ], $status_code);
    }

    /**
     * @param $id
     * @param $role_id
     * @return JsonResponse
     */
    public function assignRole($id, $role_id): JsonResponse
    {
        $user = Post::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Post not found',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($user->role_id == $role_id)
        {
            return response()->json([
                'status' => 'Success',
                'status_code'=>ResponseAlias::HTTP_OK,
                'message' => 'Post already has this role',
                'data'=>[]
            ], ResponseAlias::HTTP_OK);
        }

        $user->role_id = $role_id;
        if ($user->save()) {
            return response()->json([
                'status' => 'Success',
                'status_code'=>ResponseAlias::HTTP_OK,
                'message' => 'Role assigned successfully',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'Error',
            'status_code'=>ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Role not assigned',
            'data'=>[]
        ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param $id
     * @param $role_id
     * @return JsonResponse
     */
    public function unassignRole($id, $role_id): JsonResponse
    {
        $user = Post::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Post not found',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($user->role_id != $role_id)
        {
            return response()->json([
                'status' => 'Success',
                'status_code'=>ResponseAlias::HTTP_OK,
                'message' => 'Post already not has this role',
                'data'=>[]
            ], ResponseAlias::HTTP_OK);
        }

        $user->role_id = null;
        if ($user->save()) {
            return response()->json([
                'status' => 'Success',
                'status_code'=>ResponseAlias::HTTP_OK,
                'message' => 'Role unassigned successfully',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'Error',
            'status_code'=>ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Role not unassigned',
            'data'=>[]
        ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }
}
