<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserApiAuth
{
    public function handle(Request $request, Closure $next):mixed
    {

        if (Auth::guard('user_api')->check()) {

            return $next($request)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
        }
        return response()->json(['status_code' =>Response::HTTP_UNAUTHORIZED, 'errors' => ['Unauthorised'], 'data' => []], Response::HTTP_UNAUTHORIZED);
    }
}
