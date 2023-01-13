<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        return response()->json([
            'status' => 'Success',
            'status_code' => ResponseAlias::HTTP_OK,
            'message' => '',
            'data' => $categories
        ], ResponseAlias::HTTP_CREATED);
    }

    public function create(CreateCategoryRequest $request)
    {
        $inputs = $request->all();
        if (Category::create($inputs)) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_CREATED;
            $message = 'Category created successfully';
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Category not created';
        }

        return response()->json([
            'status' => $status,
            'status_code'=>$status_code,
            'message' => $message,
            'data'=>[]
        ], $status_code);
    }

    public function read($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'Error',
                'status_code' => ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Category not found',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => 'Success',
            'status_code'=>ResponseAlias::HTTP_OK,
            'message' => '',
            'data'=>$category,
        ], ResponseAlias::HTTP_OK);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $inputs = $request->all();
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Category not found',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($category->update($inputs)) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_OK;
            $message = 'Category updated successfully';
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Category not updated';
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
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Category not found',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($category->delete()) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_OK;
            $message = 'Category deleted successfully';
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Category not deleted';
        }

        return response()->json([
            'status' => $status,
            'status_code'=>$status_code,
            'message' => $message,
            'data'=>[]
        ], $status_code);
    }
}
