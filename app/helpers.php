<?php

/**
 * Global helper functions for the application
 */
if (! function_exists('admin_route')) {
    /**
     * Generate a URL for an admin route
     *
     * @param  string  $routeName  The route name without the admin prefix
     * @param  array|object  $parameters  Route parameters (array or model objects)
     * @return string The generated URL
     */
    function admin_route(string $routeName, $parameters = []): string
    {
        // Check the current domain to determine the correct prefix
        $host = request()->getHost();

        if ($host === 'admin.davidswood.test') {
            $prefix = 'admin.test.';
        } elseif ($host === 'admin.localhost') {
            $prefix = 'admin.local.';
        } elseif ($host === 'admin.davidswood.shop') {
            $prefix = 'admin.';
        } else {
            // Fallback for other domains
            $env = config('app.env');
            $prefix = $env === 'local' ? 'admin.test.' : 'admin.';
        }

        return route($prefix.$routeName, $parameters);
    }
}
