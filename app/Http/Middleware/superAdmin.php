<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class superAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guard('user_api')->user()->is_super_admin) {
            return $next($request);

        }
        $user = User::find($request->id);
        if($user->is_super_admin) {
            return response()->json(['message' => 'Unauthorized, Emshy yad Mn Hena 😂'], Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);

    }
}
