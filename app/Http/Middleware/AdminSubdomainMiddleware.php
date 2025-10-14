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
        // Check if this is an admin subdomain request
        $host = $request->getHost();
        $port = $request->getPort();
        
        // Handle admin.localhost:8080 or admin.davidswood.test
        if ($host === 'admin.localhost' || $host === 'admin.davidswood.test') {
            // Set the admin guard for this request
            $request->attributes->set('admin_subdomain', true);
            
            // If accessing root path and not authenticated, redirect to login
            if ($request->is('/') && !auth()->guard('admin')->check()) {
                return redirect()->route('admin.login');
            }
        }
        
        return $next($request);
    }
}
