<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserAPIAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param $privacy
     * @param null $capability
     * @return mixed
     */
    public function handle(Request $request, \Closure $next , $privacy , $capability = null)

    {
        $user = Auth::guard('user_api')->user();
        if(!$user)
        {
            return response()->json(['message' => 'Unauthorized'], ResponseAlias::HTTP_UNAUTHORIZED);
        }
        if ($user->is_super_admin)
        {
            return $next($request); // if user is super admin, he can access all pages
        }
        $user->load('role');
        $role = $user->role;
        $permistion = $role->permissions()->where('privacy', $privacy)->first();
        if(!$role || !$permistion)
        {
            return response()->json(['message' => 'access denined'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        if (is_null($capability) || in_array($capability,$permistion->capabilities)) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], ResponseAlias::HTTP_UNAUTHORIZED);

    }
}
