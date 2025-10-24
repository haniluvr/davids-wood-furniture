<?php

namespace App\Helpers;

class RouteHelper
{
    /**
     * Get the correct admin route name based on environment
     */
    public static function adminRoute(string $routeName): string
    {
        $env = config('app.env');
        
        if ($env === 'local') {
            // For local development, use test prefix
            return 'admin.test.' . $routeName;
        } else {
            // For production, use standard admin prefix
            return 'admin.' . $routeName;
        }
    }
    
    /**
     * Get the correct route name for any route based on environment
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
