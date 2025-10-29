<?php

namespace App\Helpers;

class RouteHelper
{
    /**
     * Get the correct admin route name based on environment.
     */
    public static function adminRoute(string $routeName, $parameters = []): string
    {
        $env = config('app.env');
        $httpHost = request()->server->get('HTTP_HOST');
        $host = request()->getHost();
        $port = request()->getPort();
        $scheme = request()->getScheme();

        // Determine the correct prefix based on environment and domain
        if ($env === 'local') {
            // For local development, check domain to determine prefix
            if (str_contains($httpHost, 'admin.davidswood.test')) {
                $prefix = 'admin.test.';
            } elseif (str_contains($httpHost, 'admin.localhost')) {
                $prefix = 'admin.local.';
            } else {
                $prefix = 'admin.test.'; // Default for local
            }
        } else {
            // For production, use standard admin prefix
            $prefix = 'admin.';
        }

        $url = route($prefix.$routeName, $parameters);

        // Fix URL scheme and port for specific environments
        if ($env === 'local') {
            // Fix HTTPS for admin.davidswood.test:8443
            if ($host === 'admin.davidswood.test' && $port === 8443) {
                $url = str_replace('http://admin.davidswood.test:8080', 'https://admin.davidswood.test:8443', $url);
            }

            // Fix localhost port issues
            if ($host === 'admin.localhost' && $port === 8080) {
                $url = str_replace('admin.localhost:8000', 'admin.localhost:8080', $url);
            }
        }

        return $url;
    }

    /**
     * Get the correct route name for any route based on environment.
     */
    public static function getRoute(string $routeName): string
    {
        $env = config('app.env');

        if ($env === 'local') {
            // For local development, check if it's an admin route
            if (str_starts_with($routeName, 'admin.')) {
                return str_replace('admin.', 'admin.test.', $routeName);
            }
        }

        return $routeName;
    }
}
