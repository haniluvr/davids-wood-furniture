<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SessionWishlistService;

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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only sync for guest users (not authenticated users)
        if (!auth()->check()) {
            $sessionId = session()->getId();
            
            // Sync session wishlist with database
            $this->sessionWishlistService->syncSessionWithDatabase($sessionId);
        }

        return $next($request);
    }
}
