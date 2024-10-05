<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use ApiResponse;
use Illuminate\Http\Request;

class AuthenticateApiWithSanctum
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken()) {
            if ($user = Auth::user()) {
                Auth::guard('web')->loginUsingId($user->id);
                return $next($request);
            }
        }
        return ApiResponse::sendResponse([], 401, null, true);
    }
}
