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
        return \App\Helpers\RouteHelper::adminRoute($routeName, $parameters);
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
        return \Illuminate\Support\Facades\Storage::dynamic();
    }
}

if (! function_exists('storage_url')) {
    /**
     * Get the appropriate storage URL based on environment
     * Uses local URLs for localhost, S3 URLs for production.
     */
    function storage_url(string $path): string
    {
        return \Illuminate\Support\Facades\Storage::dynamic()->url($path);
    }
}
