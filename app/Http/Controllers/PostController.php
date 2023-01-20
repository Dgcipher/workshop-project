<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\SearchPostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Services\PostService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    private $postService;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    /**
     * Display a listing of the resource.
     *
     * @param SearchPostRequest $request
     * @return JsonResponse
     */

    public function search(SearchPostRequest $request): JsonResponse
    {
        return $this->postService->search($request);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePostRequest $request
     * @return JsonResponse
     */

    public function create(CreatePostRequest $request): JsonResponse
    {
        return $this->postService->create($request);
    }
    /**
     * Display the specified resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */

    public function read($id): JsonResponse
    {
        return $this->postService->read($id);
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
        return $this->postService->update($request, $id);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    
    public function delete($id): JsonResponse
    {
        return $this->postService->delete($id);
    }
}
