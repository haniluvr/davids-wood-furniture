<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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

        if ($host === 'admin.davidswood.test') {
            config(['app.url' => 'https://admin.davidswood.test:8443']);
        } elseif ($host === 'davidswood.test') {
            config(['app.url' => 'https://davidswood.test:8443']);
        } elseif ($host === 'admin.localhost') {
            config(['app.url' => 'http://admin.localhost:8080']);
        } elseif ($host === 'localhost') {
            config(['app.url' => 'http://localhost:8080']);
        }
        
        // Register Blade directive for admin routes
        Blade::directive('adminRoute', function ($routeName) {
            return "<?php echo \\App\\Helpers\\AdminRouteHelper::route($routeName); ?>";
        });
        
        // Register global helper function
        if (!function_exists('admin_route')) {
            function admin_route(string $routeName, array $parameters = []): string {
                return \App\Helpers\AdminRouteHelper::url($routeName, $parameters);
            }
        }
    }
}
