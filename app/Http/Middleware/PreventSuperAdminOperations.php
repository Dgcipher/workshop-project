<?php

namespace App\Http\Middleware;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PreventSuperAdminOperations
{
    public function handle(Request $request, Closure $next, $privacy, $capability = null)
    {
        $user = Auth::guard('user_api')->user();
        if (!$user) {
            return response()->json(['status_code' =>Response::HTTP_UNAUTHORIZED, 'errors' => ['Unauthorised'], 'data' => []], Response::HTTP_UNAUTHORIZED);        }
        if ($user->is_super_admin) {
            return $next($request);
        }
        $userId = $request->route('id');
        if (User::find($userId)->is_super_admin) {
            return response()->json(['status_code' =>Response::HTTP_UNAUTHORIZED, 'errors' => ['Unauthorised'], 'data' => []], Response::HTTP_UNAUTHORIZED);        }

        $user->load('role');
        $role = $user->role;
        $permission = $role->permissions()->where('privacy', $privacy)->first();
        if (!$role || !$permission) {
            return response()->json(['status_code' =>Response::HTTP_UNAUTHORIZED, 'errors' => ['ACCESS DENIED'], 'data' => []], Response::HTTP_UNAUTHORIZED);
        }

        if (is_null($capability) || in_array($capability, $permission->capabilities)) {
            return $next($request);
        }

        return response()->json(['status_code' =>Response::HTTP_UNAUTHORIZED, 'errors' => ['ACCESS DENIED'], 'data' => []], Response::HTTP_UNAUTHORIZED);
    }
}
