<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RenterRole
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
        if (!Auth::check()) {
            return response()->json([
                'message' => 'You do not have permission to do this action'
            ], 403);
        }
        if (Auth::user()->role==config('const.USER.ROLE.ADMIN')
            ||Auth::user()->role==config('const.USER.ROLE.RENTER')
        ) {
            return $next($request);   
        }
        return response()->json([
            'message' => 'You do not have permission to do this action'
        ], 403);
    }
}
