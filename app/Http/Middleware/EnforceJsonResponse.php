<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnforceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (!$request->expectsJson()) {
        //     return response()->json(['message' => 'JSON response required.'], 401);
        // }

        // Log::info('EnforceJsonResponse middleware triggered.');


        if ($request->route() != null && in_array('api', $request->route()->middleware())){
            $request->headers->set('Accept', 'application/json');
        }
        return $next($request);
    }
}
