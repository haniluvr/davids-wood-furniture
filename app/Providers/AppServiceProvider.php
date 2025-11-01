<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Dynamically set APP_URL based on the request domain
        $host = request()->getHost();
        $scheme = request()->getScheme();
        $port = request()->getPort();
        $appEnv = config('app.env');

        // Production domains
        if ($appEnv === 'production') {
            if ($host === 'admin.davidswood.shop' || str_contains($host, 'admin.davidswood.shop')) {
                config(['app.url' => 'https://admin.davidswood.shop']);
            } elseif ($host === 'davidswood.shop' || str_contains($host, 'davidswood.shop')) {
                config(['app.url' => 'https://davidswood.shop']);
            } else {
                // Fallback: construct URL from current request in production
                $portString = (($scheme === 'https' && $port === 443) || ($scheme === 'http' && $port === 80)) ? '' : ':'.$port;
                config(['app.url' => $scheme.'://'.$host.$portString]);
            }
        }
        // Local development domains
        elseif ($appEnv === 'local') {
            if ($host === 'admin.davidswood.test') {
                config(['app.url' => 'https://admin.davidswood.test:8443']);
            } elseif ($host === 'davidswood.test') {
                config(['app.url' => 'https://davidswood.test:8443']);
            } elseif ($host === 'admin.localhost') {
                config(['app.url' => 'http://admin.localhost:8080']);
            } elseif ($host === 'localhost') {
                config(['app.url' => 'http://localhost:8080']);
            }
        }
        // For other environments, use env() or construct from request
        else {
            $envUrl = env('APP_URL');
            if (! $envUrl) {
                $portString = (($scheme === 'https' && $port === 443) || ($scheme === 'http' && $port === 80)) ? '' : ':'.$port;
                config(['app.url' => $scheme.'://'.$host.$portString]);
            }
        }

        // Register Blade directive for admin routes
        Blade::directive('adminRoute', function ($routeName) {
            return "<?php echo \\App\\Helpers\\AdminRouteHelper::route($routeName); ?>";
        });
    }
}
