<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::get();
        return response()->json([
            'status' => 'Success',
            'status_code' => ResponseAlias::HTTP_OK,
            'message' => '',
            'data' => $posts,
        ], ResponseAlias::HTTP_CREATED);
    }

    public function create(CreatePostRequest $request)
    {
        // $inputs = $request->all();
        $ImageName = time() . '.' .$request->title . '.'. $request->image->extension();
        $request->image->move(public_path('images'), $ImageName);
        //$path = "public/images/$ImageName";

        if (Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'image_path' => $ImageName
        ])) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_CREATED;
            $message = 'Post created successfully';
        } else {
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Post not created';
        }

        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'message' => $message,
            'data' => []
        ], $status_code);
    }

    public function read($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => 'Error',
                'status_code' => ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Post not found',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => 'Success',
            'status_code' => ResponseAlias::HTTP_OK,
            'message' => '',
            'data' => $post,
        ], ResponseAlias::HTTP_OK);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $inputs = $request->all();
        $post = Post::find($id);
        $ImageName = time() . '.' .$request->title . '.'. $request->image->extension();
        $request->image->move(public_path('images'), $ImageName);
        $post->image_path = $ImageName;

        if (!$post) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Post not found',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($post->update($inputs)) {
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

    public function delete($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => 'Error',
                'status_code' => ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Post not found',
                'data' => []
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($post->delete()) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_OK;
            $message = 'Post deleted successfully';
        } else {
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Post not deleted';
        }

        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'message' => $message,
            'data' => []
        ], $status_code);
    }
}
