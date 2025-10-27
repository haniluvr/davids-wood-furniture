<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Override the storage URL to be dynamic based on current request
        Storage::macro('getDynamicUrl', function ($path) {
            $scheme = request()->getScheme();
            $host = request()->getHost();
            $port = request()->getPort();

            // Don't include port if it's the default for the scheme
            $portString = ($scheme === 'https' && $port === 443) || ($scheme === 'http' && $port === 80) ? '' : ':'.$port;

            return $scheme.'://'.$host.$portString.'/storage/'.ltrim($path, '/');
        });

        // Add macro for dynamic storage disk selection
        Storage::macro('getDynamicDisk', function () {
            $env = config('app.env');
            $host = request()->getHost();
            
            // Use S3 if explicitly set in environment (highest priority)
            if (env('FILESYSTEM_DISK') === 's3') {
                return 's3';
            }
            
            // Use S3 for production environment
            if ($env === 'production') {
                return 's3';
            }
            
            // Use local storage for localhost development
            if (str_contains($host, 'localhost') || str_contains($host, '127.0.0.1') || str_contains($host, '.test')) {
                return 'public';
            }
            
            // Default to public for other environments
            return 'public';
        });

        // Add macro for dynamic storage operations
        Storage::macro('dynamic', function () {
            $disk = Storage::getDynamicDisk();

            return Storage::disk($disk);
        });
    }
}
