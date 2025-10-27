<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreIntendedUrl
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only store intended URL for GET requests to avoid storing POST/PUT/DELETE URLs
        if ($request->isMethod('GET') && ! $request->expectsJson()) {
            // Don't store URLs for login, register, or auth routes to avoid redirect loops
            $currentPath = $request->path();
            $excludedPaths = ['login', 'register', 'auth/google', 'auth/google/callback'];

            $shouldStore = true;
            foreach ($excludedPaths as $excludedPath) {
                if (str_starts_with($currentPath, $excludedPath)) {
                    $shouldStore = false;

                    break;
                }
            }

            if ($shouldStore) {
                // Store the current URL as the intended URL for redirect after login
                session()->put('url.intended', $request->fullUrl());
            }
        }

        return $next($request);
    }
}
