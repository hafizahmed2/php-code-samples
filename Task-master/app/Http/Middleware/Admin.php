<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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

        if (\Auth::guard('admin-api')->user() && \Auth::guard('admin-api')->user()->role == "Admin") {
            return $next($request);
     }

        return response()->json([
            'success'=>true,
            'message' => 'Unauthorized'
        ], 401);
    }
}
