<?php

namespace App\Http\Middleware;

use App\Models\AdminPermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Check if user is authenticated as admin
        if (! Auth::guard('admin')->check()) {
            return redirect()->to(admin_route('login'))->with('error', 'Please log in to access this page.');
        }

        $admin = Auth::guard('admin')->user();

        // Super admin has all permissions
        if ($admin->role === 'super_admin') {
            return $next($request);
        }

        // Check if admin has the required permission
        if (! AdminPermission::hasPermission($admin->role, $permission)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
