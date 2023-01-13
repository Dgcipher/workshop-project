<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (\Illuminate\Http\Response|RedirectResponse) $next
     *
     */
    public function handle(Request $request, Closure $next)
    {
        $sup=Auth::guard('user_api')->user()->is_super_admin;
        if ($sup) {
            return $next($request);
        }

        #chech is this update to the id of Admin
        $user = User::find($request->route('id'))->is_super_admin;
        if ($user) {
            return response()->json(['message' => '!! this is super_Admin,You are not authorized to do this action'], Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);

    }
}
