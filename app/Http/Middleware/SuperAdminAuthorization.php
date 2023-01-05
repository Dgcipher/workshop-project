<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminAuthorization
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
        if (Auth::guard('user_api')->user()->is_super_admin) {
            return $next($request);
        }
        $user = User::find($request->route('id'));
        if ($user->is_super_admin) {
            return response()->json(['message' => 'You are not authorized to do this action'], Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
