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
        // Only log session ID if session is available
        $sessionIdBefore = null;
        try {
            $sessionIdBefore = session()->getId();
        } catch (\Exception $e) {
            // Session not available yet, that's okay
        }

        \Log::info('AdminSubdomainMiddleware: Middleware called', [
            'middleware' => 'AdminSubdomainMiddleware',
            'session_id_before' => $sessionIdBefore,
            'url' => $request->url(),
            'method' => $request->method(),
            'host' => $request->getHost(),
            'port' => $request->getPort(),
        ]);

        // Check if this is an admin subdomain request
        $host = $request->getHost();
        $port = $request->getPort();

        // Handle admin.localhost:8080 or admin.davidswood.test
        if ($host === 'admin.localhost' || $host === 'admin.davidswood.test') {
            // Set the admin guard for this request
            $request->attributes->set('admin_subdomain', true);

            // If accessing root path and not authenticated, redirect to login
            if ($request->is('/') && ! auth()->guard('admin')->check()) {
                return redirect()->route('admin.login');
            }
        }

        $response = $next($request);

        // Only log session ID if session is available
        $sessionIdAfter = null;
        try {
            $sessionIdAfter = session()->getId();
        } catch (\Exception $e) {
            // Session not available, that's okay
        }

        \Log::info('AdminSubdomainMiddleware: Middleware completed', [
            'middleware' => 'AdminSubdomainMiddleware',
            'session_id_before' => $sessionIdBefore,
            'session_id_after' => $sessionIdAfter,
            'session_changed' => $sessionIdBefore !== $sessionIdAfter,
            'url' => $request->url(),
        ]);

        return $response;
    }
}
