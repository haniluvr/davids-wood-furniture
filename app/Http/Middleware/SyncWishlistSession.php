<?php

namespace App\Http\Middleware;

use App\Services\SessionWishlistService;
use Closure;
use Illuminate\Http\Request;

class SyncWishlistSession
{
    protected $sessionWishlistService;

    public function __construct(SessionWishlistService $sessionWishlistService)
    {
        $this->sessionWishlistService = $sessionWishlistService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $sessionIdBefore = session()->getId();

        \Log::info('SyncWishlistSession: Middleware called', [
            'middleware' => 'SyncWishlistSession',
            'session_id_before' => $sessionIdBefore,
            'url' => $request->url(),
            'method' => $request->method(),
            'auth_check' => auth()->check(),
            'user_id' => auth()->id(),
        ]);

        // Only sync for guest users (not authenticated users)
        if (! auth()->check()) {
            $sessionId = session()->getId();

            // Sync session wishlist with database
            $this->sessionWishlistService->syncSessionWithDatabase($sessionId);
        }

        $response = $next($request);

        $sessionIdAfter = session()->getId();

        \Log::info('SyncWishlistSession: Middleware completed', [
            'middleware' => 'SyncWishlistSession',
            'session_id_before' => $sessionIdBefore,
            'session_id_after' => $sessionIdAfter,
            'session_changed' => $sessionIdBefore !== $sessionIdAfter,
            'url' => $request->url(),
        ]);

        return $response;
    }
}
