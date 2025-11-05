<?php

/**
 * Global helper functions for the application.
 */
if (! function_exists('admin_route')) {
    /**
     * Generate a URL for an admin route.
     *
     * @param string $routeName The route name without the admin prefix
     * @param array|object $parameters Route parameters (array or model objects)
     * @return string The generated URL
     */
    function admin_route(string $routeName, $parameters = []): string
    {
        // Use RouteHelper for consistent route generation
        // Use class_exists check to ensure the class is available
        if (class_exists(\App\Helpers\RouteHelper::class)) {
            return \App\Helpers\RouteHelper::adminRoute($routeName, $parameters);
        }

        // Fallback: Use the route helper directly if RouteHelper class not found
        $env = config('app.env');
        $prefix = $env === 'local' ? 'admin.test.' : 'admin.';

        return route($prefix.$routeName, $parameters);
    }
}

if (! function_exists('storage_disk')) {
    /**
     * Get the appropriate storage disk based on environment
     * Uses local storage for localhost, S3 for production.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    function storage_disk()
    {
        try {
            if (method_exists(\Illuminate\Support\Facades\Storage::class, 'dynamic')) {
                return \Illuminate\Support\Facades\Storage::dynamic();
            }
        } catch (\Exception $e) {
            // Fallback if dynamic method doesn't exist
        }

        // Fallback to public disk
        return \Illuminate\Support\Facades\Storage::disk('public');
    }
}

if (! function_exists('storage_url')) {
    /**
     * Get the appropriate storage URL based on environment
     * Uses local URLs for localhost, S3 URLs for production.
     */
    function storage_url(string $path): string
    {
        try {
            if (method_exists(\Illuminate\Support\Facades\Storage::class, 'dynamic')) {
                return \Illuminate\Support\Facades\Storage::dynamic()->url($path);
            }
        } catch (\Exception $e) {
            // Fallback if dynamic method doesn't exist
        }

        // Fallback to asset helper
        return asset('storage/'.ltrim($path, '/'));
    }
}
