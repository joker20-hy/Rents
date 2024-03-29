<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OwnerRole
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
        if (Auth::user()->role!=config('const.USER.ROLE.OWNER')) {
            return response()->json([
                'message' => 'You do not have permission to do this action'
            ], 403);
        }
        return $next($request);
    }
}
