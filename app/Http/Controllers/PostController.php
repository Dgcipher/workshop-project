<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\SearchPostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Traits\UploadTrait;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    use UploadTrait;
    private $postModel;
    public function __construct(Post $post)
    {
        $this->postModel = $post;
    }
    /**
     * Display a listing of the resource.
     *
     * @param SearchPostRequest $request
     * @return JsonResponse
     */
    public function search(SearchPostRequest $request): JsonResponse
    {
        $select = $request->input('select', ['*']);
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $data = $this->postModel::simplePaginate($per_page, $select, 'page', $page);

        return response()->json([
            'status' => 'Success',
            'status_code' => Response::HTTP_CREATED,
            'message' => '',
            'data' => $data
        ], Response::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePostRequest $request
     * @return JsonResponse
     */

    public function create(CreatePostRequest $request): JsonResponse
    {

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $this->uploadFile($image, 'posts/images/', $imageName);
        }
        $this->postModel::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName
        ]);
        return response()->json([
            'status' => 'Success',
            'status_code' => Response::HTTP_CREATED,
            'message' => 'Post Created Successfully',
            'data' => []
        ], Response::HTTP_CREATED);
    }
    /**
     * Display the specified resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function read($id): JsonResponse
    {
        $post = $this->postModel::findOrFail($id);
        if (!$post) {
            return response()->json([
                'status' => 'Error',
                'status_code' => Response::HTTP_NOT_FOUND,
                'message' => 'Post not found', 'data' => []
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => 'Success',
            'status_code' => Response::HTTP_OK,
            'message' => '',
            'data' => $post->only(['id', 'title', 'description'])
        ], Response::HTTP_OK);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePostRequest $request
     * @param $id
     * @return JsonResponse
     */

    public function update(UpdatePostRequest $request, $id): JsonResponse
    {
        $post = $this->postModel::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $this->uploadFile($image, 'posts/images/', $imageName, 'posts/images/' . $post->image);
        }
        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => (isset($imageName)) ? $imageName : $post->image
        ]);
        return response()->json([
            'status' => 'Success',
            'status_code' => Response::HTTP_OK,
            'message' => 'Post Updated Successfully',
            'data' => []
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $post = $this->postModel::findOrFail($id);
        $post->delete();
        if ($post->image) {
            $this->deleteFile('posts/images/' .$post->image);
            return response()->json([
                'status' => 'Success',
                'status_code' => Response::HTTP_OK,
                'message' => 'Post Deleted Successfully',
                'data' => []
            ], Response::HTTP_OK);
        }
    }
}
