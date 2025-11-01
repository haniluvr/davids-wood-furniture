<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user has 2FA enabled
        if ($user && $user->two_factor_enabled) {
            // Check if 2FA has been verified in this session
            if (! session('two_factor_verified')) {
                // Check if there's a pending 2FA verification
                if (session('pending_2fa_user_id') && session('pending_2fa_user_id') == $user->id) {
                    return redirect()->route('auth.check-email')
                        ->with('info', 'Please check your email to complete login');
                }

                // If no pending 2FA, redirect to login
                return redirect()->route('login')
                    ->with('error', 'Two-factor authentication required');
            }
        }

        return $next($request);
    }
}
