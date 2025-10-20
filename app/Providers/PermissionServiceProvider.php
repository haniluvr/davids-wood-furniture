<?php

namespace App\Providers;

use App\Models\AdminPermission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
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
        // Register permission gates
        Gate::define('admin-permission', function ($user, $permission) {
            if (! $user || ! $user->role) {
                return false;
            }

            // Super admin has all permissions
            if ($user->role === 'super_admin') {
                return true;
            }

            return AdminPermission::hasPermission($user->role, $permission);
        });

        // Define specific permission gates
        $permissions = AdminPermission::getAllPermissions();

        foreach ($permissions as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                if (! $user || ! $user->role) {
                    return false;
                }

                // Super admin has all permissions
                if ($user->role === 'super_admin') {
                    return true;
                }

                return AdminPermission::hasPermission($user->role, $permission);
            });
        }
    }
}
