<?php

namespace App\Http\Services;

use App\Http\Repositories\PostRepository;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\UploadTrait;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PostService
{
    use UploadTrait;
    use ApiResponseTrait;
    private $postRepository;
    private $postModel;
    public function __construct(PostRepository $postRepository, Post $post)
    {
        $this->postRepository = $postRepository;
        $this->postModel = $post;
    }

    public function search($request): JsonResponse
    {
        $select = $request->input('select', ['*']);
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $data = $this->postRepository->search($per_page, $select, $page);

        return $this->success('Success', Response::HTTP_CREATED, "", $data);
    }

    public function create($request): JsonResponse
    {

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $this->uploadFile($image, 'posts/images/', $imageName);
        }
        $data =[
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName
        ];
        $this->postRepository->create($data);
        return $this->success('Success', Response::HTTP_CREATED, 'Post Created Successfully', []);
     }

    public function read($id): JsonResponse
    {
        $post = $this->postRepository->read($id);
        if (!$post) {
            return $this->error('Error', Response::HTTP_NOT_FOUND, 'Post not found', []);
        }
        return $this->success('Success', Response::HTTP_OK, '', $post->only(['id', 'title', 'description']));
    }

    public function update($request, $id): JsonResponse
    {
        $post = $this->postRepository->findOrFail($id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $this->uploadFile($image, 'posts/images/', $imageName, 'posts/images/' . $post->image);
        }
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'image' => (isset($imageName)) ? $imageName : $post->image
        ];
        $this->postRepository->update($data, $id);
        return $this->success('Success', Response::HTTP_OK, 'Post Updated Successfully', []);
    }

    public function delete($id): JsonResponse
    {
        $post = $this->postRepository->delete($id);
        $post->delete();
        if ($post->image) {
            $this->deleteFile('posts/images/' . $post->image);
            return $this->success('Success', Response::HTTP_OK, 'Post Deleted Successfully', []);
        }
    }
 }
