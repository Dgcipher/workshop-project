<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class NotSuperAdmin
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
        $admin = Auth::guard('user_api')->user();
        if ($admin->is_super_admin) {
            return $next($request);
        }
        $updateduser = User::find($request->route('id'));
        if(!$updateduser){
            return response()->json(['message' => 'User Not Found'], ResponseAlias::HTTP_NOT_FOUND);
        }
        if($updateduser->is_super_admin){
            return response()->json(['message'=>'Can Not Edit Or Delete The Super Admin'],ResponseAlias::HTTP_UNAUTHORIZED);
        }
        return $next($request);

    }





}
