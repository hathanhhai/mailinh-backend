<?php

namespace App\Http\Middleware;

use Closure;

use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class JWT
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        try {
            $user = FacadesJWTAuth::parseToken()->authenticate();
            if($user){
                return $next($request);
            }
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 401, 'success' => false, 'message' => 'Token invalid']);
        }

    }
}
