<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminSubdomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        
        // Check if this is an admin subdomain (regardless of port)
        $isAdminSubdomain = str_starts_with($host, 'admin.');
        
        if (!$isAdminSubdomain) {
            // If not an admin subdomain, continue to next middleware/route
            return $next($request);
        }
        
        // This is an admin subdomain, continue with the request
        return $next($request);
    }
}