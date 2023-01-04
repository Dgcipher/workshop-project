<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\SearchPostsRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
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
            'status_code' => Response::HTTP_CREATED,
            'message' => '',
            'data' => $data
        ],Response::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePostRequest $request
     * @return JsonResponse
     */
    public function create(CreatePostRequest $request): JsonResponse
    {
        $inputs = $request->except('image');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->move(public_path('posts/images/'), $imageName);
            $inputs['image'] = $imageName;
        }

        if (Post::create($inputs)) {
            $status = 'Success';
            $status_code = Response::HTTP_CREATED;
            $message = 'Post was created successfully';
        } else {
            $status = 'Error';
            $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Post was not created';
        }

        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'message' => $message,
            'data' => []
        ], $status_code);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function read(int $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            $status = 'Error';
            $status_code = Response::HTTP_NOT_FOUND;
            $message = 'Post not found';
            $data = [];
        } else {
            $status = 'Success';
            $status_code = Response::HTTP_OK;
            $message = '';
            $data = $post->only(['id','title','body', 'image']);
        }

        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'message' => $message,
            'data' => $data
        ], $status_code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePostRequest $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdatePostRequest $request, int $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>Response::HTTP_NOT_FOUND,
                'message' => 'Post not found',
                'data'=>[]
            ], Response::HTTP_NOT_FOUND);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->move(public_path('posts/images/'), $imageName);
            $oldImagPath = public_path('posts/images/') . $post->image;
            if (File::exists($oldImagPath)) {
                File::delete($oldImagPath);
            }
        }

        $post->update([
            'title' => $request->title,
            'description' => $request->body,
            'image' => (isset($imageName)) ? $imageName : $post->image
        ]);

        return response()->json([
            'status' => 'Success',
            'status_code' => Response::HTTP_OK,
            'message' => 'Post was updated successfully',
            'data' => []
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>Response::HTTP_NOT_FOUND,
                'message' => 'Post not found',
                'data'=>[]
            ], Response::HTTP_NOT_FOUND);
        }

        $post->delete();

        if ($post->image) {
            File::delete(public_path('posts/images/') . $post->image);
        }

        return response()->json([
            'status' => 'Success',
            'status_code' => Response::HTTP_OK,
            'message' => 'Post was deleted successfully',
            'data' => []
        ], Response::HTTP_OK);
    }
}
