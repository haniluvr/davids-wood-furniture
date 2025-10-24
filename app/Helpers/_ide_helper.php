<?php

/**
 * IDE Helper file for custom functions
 * This file helps IDEs and linters understand custom functions defined at runtime
 */

/**
 * Generate a URL for an admin route with environment-aware routing
 * 
 * @param string $routeName The route name without the admin prefix
 * @param array|object $parameters Route parameters (array or model objects)
 * @return string The generated URL
 */
function admin_route(string $routeName, $parameters = []): string {
    return \App\Helpers\AdminRouteHelper::url($routeName, $parameters);
}
