<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Create a custom middleware group for API routes that need sessions but not CSRF
        $middleware->group('api.session', [
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\CaptureGuestSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\SyncWishlistSession::class,
        ]);
        
        // Register admin middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminAuth::class,
            'admin.subdomain' => \App\Http\Middleware\AdminSubdomainMiddleware::class,
            'admin.permission' => \App\Http\Middleware\CheckAdminPermission::class,
            'force.https' => \App\Http\Middleware\ForceHttps::class,
            'store.intended' => \App\Http\Middleware\StoreIntendedUrl::class,
        ]);
        
        // Exclude CSRF from specific routes
        $middleware->validateCsrfTokens(except: [
            'api/cart/*',
            'api/wishlist/*',
            'api/products/*',
            'api/check-username/*'
        ]);
        
        // Add CORS middleware to API routes
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
        
        // Add CORS middleware to web routes for cart/wishlist API endpoints
        $middleware->web(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
            // \App\Http\Middleware\ForceHttps::class, // Temporarily disabled for testing
        ]);
        
        // Add AdminSubdomainMiddleware after session is started
        $middleware->web(append: [
            \App\Http\Middleware\AdminSubdomainMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
