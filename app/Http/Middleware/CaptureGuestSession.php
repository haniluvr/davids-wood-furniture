<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CaptureGuestSession
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
        // Capture session ID at the very beginning of the request
        // This ensures we have the original session ID before any regeneration
        if (!auth()->check()) {
            $originalSessionId = session()->getId();
            
            // Store the original session ID in the request for later use
            $request->merge(['original_session_id' => $originalSessionId]);
            
            \Log::info('CaptureGuestSession: Original session ID captured', [
                'original_session_id' => $originalSessionId,
                'url' => $request->url(),
                'method' => $request->method()
            ]);
        }

        return $next($request);
    }
}
