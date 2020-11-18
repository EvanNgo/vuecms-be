<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermisisonCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!$request->user()->hasPermission($permission)) {
            return response()->json(['error' => 'Permission invalid.', 'status_code' => 401], 401);
        }
        return $next($request);
    }
}
