<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

/**
 * Helper class for environment-aware admin routing.
 */
class AdminRouteHelper
{
    /**
     * Get the environment-aware route name for admin routes.
     *
     * @param string $routeName The route name without the admin prefix
     * @return string The full route name with appropriate prefix
     */
    public static function route(string $routeName): string
    {
        $env = config('app.env');

        if ($env === 'local') {
            return 'admin.test.'.$routeName;
        } else {
            return 'admin.'.$routeName;
        }
    }

    /**
     * Generate a URL for an admin route.
     *
     * @param string $routeName The route name without the admin prefix
     * @param array|object $parameters Route parameters (array or model objects)
     * @return string The generated URL
     */
    public static function url(string $routeName, $parameters = []): string
    {
        return route(self::route($routeName), $parameters);
    }
}

/*
 * Global helper function for admin routes
 *
 * @param  string  $routeName  The route name without the admin prefix
 * @param  array|object  $parameters  Route parameters (array or model objects)
 * @return string The generated URL
 */
if (! function_exists('admin_route')) {
    function admin_route(string $routeName, $parameters = []): string
    {
        return \App\Helpers\AdminRouteHelper::url($routeName, $parameters);
    }
}
