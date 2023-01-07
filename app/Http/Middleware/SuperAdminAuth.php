<?php

namespace App\Http\Middleware;

use App\Methods\Helpers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminAuth
{
    use Helpers;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
//        $curren_user = Auth::guard('user_api')->user()->is_super_admin;
//        if($curren_user) {
//            return $next($request);
//        }

        // to get user that will be updated or deleted
        $user = User::find($request->id);
        if($user->is_super_admin ) {
            return  $this->responseJson(0,"Access denied");
        }
        return $next($request);
    }
}
