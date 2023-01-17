<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUsersRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SearchUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $user= User::where('email', $credentials['email'])->first();
        if (!$user)
        {
            return response()->json(['message' => 'email or password is incorrect'], ResponseAlias::HTTP_UNAUTHORIZED);
        }
        if (Hash::check($credentials['password'], $user->password)) {
            $token = $user->createToken('user_token');
            return response()->json([
                'token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_in' => $token->token->expires_at->diffInSeconds(now()),
            ], ResponseAlias::HTTP_OK);
        } else {
            return response()->json(['error' => 'email or password is incorrect'], ResponseAlias::HTTP_UNAUTHORIZED);
        }
    }

    public function search(SearchUsersRequest $request): JsonResponse
    {
        $select = $request->input('select', ['*']);
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $data = User::simplePaginate($per_page, $select, 'page', $page);

        return response()->json([
            'status' => 'Success',
            'status_code'=>ResponseAlias::HTTP_CREATED,
            'message'=>'',
            'data' => $data
        ], ResponseAlias::HTTP_CREATED);
    }


    public function create(CreateUsersRequest $request): JsonResponse
    {
        $inputs = $request->all();
        $inputs['password'] = bcrypt($inputs['password']);

        if (User::create($inputs)) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_CREATED;
            $message = 'User created successfully';
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'User not created';
        }

        return response()->json([
            'status' => $status,
            'status_code'=>$status_code,
            'message' => $message,
            'data'=>[]
        ], $status_code);
    }


    public function read($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'User not found','data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => 'Success',
            'status_code'=>ResponseAlias::HTTP_OK,
            'message' => '',
            'data'=>$user->only(['id','name','email'])
        ], ResponseAlias::HTTP_OK);
    }


    public function update(UpdateUsersRequest $request, $id): JsonResponse
    {
        $inputs = $request->all();
        if ($request->has('password')) {
            $inputs['password'] = bcrypt($inputs['password']);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'User not found',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($user->update($inputs)) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_OK;
            $message = 'User updated successfully';
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'User not updated';
        }

        return response()->json([
            'status' => $status,
            'status_code'=>$status_code,
            'message' => $message,
            'data'=>[]
        ], $status_code);

    }


    public function delete($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'User not found',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($user->delete()) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_OK;
            $message = 'User deleted successfully';
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'User not deleted';
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
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'User not found',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($user->role_id == $role_id)
        {
            return response()->json([
                'status' => 'Success',
                'status_code'=>ResponseAlias::HTTP_OK,
                'message' => 'User already has this role',
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
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'User not found',
                'data'=>[]
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($user->role_id != $role_id)
        {
            return response()->json([
                'status' => 'Success',
                'status_code'=>ResponseAlias::HTTP_OK,
                'message' => 'User already not has this role',
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
