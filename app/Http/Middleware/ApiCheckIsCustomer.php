<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiCheckIsCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard="api_customer")
    {
        if(!auth()->guard($guard)->check()) {
            Auth::logout();
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
                'data' => []
            ], 401);
        }
        return $next($request);
    }
}
