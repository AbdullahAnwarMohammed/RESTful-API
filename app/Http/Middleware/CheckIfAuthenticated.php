<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return response()->json([$request->user()]);
        if ($request->user()) {
            // User is authenticated
            return $next($request);
        }

        // User is not authenticated
        return response()->json([
            'error' => 'Unauthorized',
            'message' => 'You must be authenticated to access this resource.'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
