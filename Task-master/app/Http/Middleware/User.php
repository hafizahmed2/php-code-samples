<?php

namespace App\Http\Middleware;

use Closure;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       if (\Auth::guard('user-api')->user() && \Auth::guard('user-api')->user()->role == "User") {
            return $next($request);
     }

        return response()->json([
            'success'=>true,
            'message' => 'Unauthorized'
        ], 401);
    }
}