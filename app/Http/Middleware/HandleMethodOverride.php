<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleMethodOverride
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('_method')) {
            $method = strtoupper($request->input('_method'));

            // Only allow certain HTTP methods for method override
            if (in_array($method, ['PUT', 'PATCH', 'DELETE'])) {
                $request->setMethod($method);
            }
        }

        return $next($request);
    }
}
