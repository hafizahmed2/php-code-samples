<?php

namespace App\Http\Middleware;

use Closure;

class Bloger
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
        if (\Auth::guard('bloger-api')->user() && \Auth::guard('bloger-api')->user()->role == "Bloger") {
            return $next($request);
     }

        return response()->json([
            'success'=>true,
            'message' => 'Unauthorized'
        ], 401);
    }
}
