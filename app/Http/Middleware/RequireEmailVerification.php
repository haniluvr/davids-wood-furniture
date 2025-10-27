<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user is authenticated and email is not verified
        if ($user && ! $user->email_verified_at) {
            return redirect()->route('auth.verify-email-sent')
                ->with('info', 'Please verify your email address to continue.');
        }

        return $next($request);
    }
}
