<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiVersion
{
    /**
     * Handle an incoming request and set API version
     */
    public function handle(Request $request, Closure $next, string $version = 'v1')
    {
        $request->attributes->set('api_version', $version);
        
        // Add version header to response
        $response = $next($request);
        $response->headers->set('X-API-Version', $version);
        
        return $response;
    }
}
