<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignPermissionsRequest;
use App\Http\Requests\CreateRolesRequest;
use App\Http\Requests\SearchRolesRequest;
use App\Http\Requests\UnassignPermissionsRequest;
use App\Http\Requests\UpdateCapabilitiesRequest;
use App\Http\Requests\UpdateRolesRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SearchRolesRequest $request
     * @return JsonResponse
     */
    public function search(SearchRolesRequest $request): JsonResponse
    {
        $select = $request->input('select', ['*']);
        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $data = Role::simplePaginate($per_page, $select, 'page', $page);

        return response()->json([
            'status' => 'Success',
            'status_code'=>ResponseAlias::HTTP_OK,
            'message'=>'',
            'data' => $data
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRolesRequest $request
     * @return JsonResponse
     */
    public function create(CreateRolesRequest $request): JsonResponse
    {
        $inputs = $request->all();

        if (Role::create($inputs)) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_CREATED;
            $message = 'Role created successfully';
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Role not created';
        }

        return response()->json([
            'status' => $status,
            'status_code'=>$status_code,
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
        $data = Role::find($id);

        if ($data) {
            $status = 'Success';
            $status_code = ResponseAlias::HTTP_OK;
            $message = '';
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_NOT_FOUND;
            $message = 'Role not found';
        }

        return response()->json([
            'status' => $status,
            'status_code'=>$status_code,
            'message' => $message,
            'data' => $data
        ], $status_code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRolesRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateRolesRequest $request, int $id): JsonResponse
    {
        $inputs = $request->all();

        $data = Role::find($id);

        if ($data) {
            if ($data->update($inputs)) {
                $status = 'Success';
                $status_code = ResponseAlias::HTTP_OK;
                $message = 'Role updated successfully';
            }else{
                $status = 'Error';
                $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
                $message = 'Role not updated';
            }
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_NOT_FOUND;
            $message = 'Role not found';
        }

        return response()->json([
            'status' => $status,
            'status_code'=>$status_code,
            'message' => $message,
            'data' => []
        ], $status_code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $data = Role::find($id);

        if ($data) {
            if ($data->delete()) {
                $status = 'Success';
                $status_code = ResponseAlias::HTTP_OK;
                $message = 'Role deleted successfully';
            }else{
                $status = 'Error';
                $status_code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
                $message = 'Role not deleted';
            }
        }else{
            $status = 'Error';
            $status_code = ResponseAlias::HTTP_NOT_FOUND;
            $message = 'Role not found';
        }

        return response()->json([
            'status' => $status,
            'status_code'=>$status_code,
            'message' => $message,
            'data' => []
        ], $status_code);
    }

    public function getPermissions($id): JsonResponse
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Role not found',
                'data' => []
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => 'Success',
            'status_code'=>ResponseAlias::HTTP_OK,
            'message' => '',
            'data' => $role->permissions()->get()?->only('privacy','capabilities') ?: []
        ], ResponseAlias::HTTP_OK);

    }
    public function assignPermissions(AssignPermissionsRequest $request,$id)
    {


        $role = Role::find($id);
        if (!$role) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Role not found',
                'data' => []
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
        $role->permissions()->where('privacy',$request->privacy)->where('capabilities',$request->capabilities)->exists();
        if ($role->permissions()->create($request->only('privacy','capabilities')))
        {
            return response()->json([
                'status' => 'Success',
                'status_code'=>ResponseAlias::HTTP_OK,
                'message' => 'Permissions assigned successfully',
                'data' => []
            ], ResponseAlias::HTTP_OK);
        }else{
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Permissions not assigned',
                'data' => []
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
    public function unassignPermissions(UnassignPermissionsRequest $request,$id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Role not found',
                'data' => []
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
        $privacy = $request->input('privacy');
        if ($role->permissions()->where('privacy',$privacy)->delete())
        {
            return response()->json([
                'status' => 'Success',
                'status_code'=>ResponseAlias::HTTP_OK,
                'message' => 'Permissions unassigned successfully',
                'data' => []
            ], ResponseAlias::HTTP_OK);
        }else{
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Permissions not unassigned',
                'data' => []
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }

     }
    public function updateCapabilities(UpdateCapabilitiesRequest $request,$id,$privacy): JsonResponse
    {
        $capabilities = $request->input('capabilities');

        $permissions = Permission::where('role_id',$id)->where('privacy',$privacy)->first();

        if (!$permissions){
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Permissions not found',
                'data' => []
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($permissions->update(['capabilities'=>$capabilities]))
        {
            return response()->json([
                'status' => 'Success',
                'status_code'=>ResponseAlias::HTTP_OK,
                'message' => 'Capabilities updated successfully',
                'data' => []
            ], ResponseAlias::HTTP_OK);

        }else{

            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Capabilities not updated',
                'data' => []
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
    public function assignCapabilities(UpdateCapabilitiesRequest $request,$id,$privacy)
    {
        $capabilities = $request->input('capabilities');

        $permissions = Permission::where('role_id',$id)->where('privacy',$privacy)->first();

        if (!$permissions){
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Permissions not found',
                'data' => []
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $capabilities = array_merge($permissions->capabilities,$capabilities);

        if ($permissions->update(['capabilities'=>$capabilities]))
        {
            return response()->json([
                'status' => 'Success',
                'status_code'=>ResponseAlias::HTTP_OK,
                'message' => 'Capabilities updated successfully',
                'data' => []
            ], ResponseAlias::HTTP_OK);

        }else{

            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Capabilities not updated',
                'data' => []
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function unassignCapabilities(UpdateCapabilitiesRequest $request,$id,$privacy)
    {
        $capabilities = $request->input('capabilities');

        $permissions = Permission::where('role_id',$id)->where('privacy',$privacy)->first();

        if (!$permissions){
            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'Permissions not found',
                'data' => []
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $capabilities = array_diff($permissions->capabilities,$capabilities);

        if ($permissions->update(['capabilities'=>$capabilities]))
        {
            return response()->json([
                'status' => 'Success',
                'status_code'=>ResponseAlias::HTTP_OK,
                'message' => 'Capabilities updated successfully',
                'data' => []
            ], ResponseAlias::HTTP_OK);

        }else{

            return response()->json([
                'status' => 'Error',
                'status_code'=>ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Capabilities not updated',
                'data' => []
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
