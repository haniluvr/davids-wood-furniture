<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPermission;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index()
    {
        $roles = ['super_admin', 'admin', 'manager', 'staff', 'viewer'];
        $permissions = AdminPermission::getAllPermissions();
        
        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role] = AdminPermission::getRolePermissions($role);
        }

        return view('admin.permissions.index', compact('roles', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'required|array',
            'permissions.*.*' => 'boolean',
        ]);

        $permissions = $request->input('permissions');
        
        foreach ($permissions as $role => $rolePermissions) {
            foreach ($rolePermissions as $permission => $granted) {
                AdminPermission::updateOrCreate(
                    ['role' => $role, 'permission' => $permission],
                    ['granted' => $granted]
                );
            }
        }

        // Log the action
        AuditLog::create([
            'admin_id' => Auth::id(),
            'action' => 'permissions_updated',
            'model_type' => AdminPermission::class,
            'model_id' => null,
            'old_values' => null,
            'new_values' => $permissions,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permissions updated successfully.');
    }

    public function resetToDefaults()
    {
        // Clear existing permissions
        AdminPermission::truncate();

        // Get default role permissions
        $defaultPermissions = AdminPermission::getDefaultRolePermissions();

        // Create permissions for each role
        foreach ($defaultPermissions as $role => $permissions) {
            foreach ($permissions as $permission) {
                AdminPermission::create([
                    'role' => $role,
                    'permission' => $permission,
                    'granted' => true,
                ]);
            }
        }

        // Log the action
        AuditLog::create([
            'admin_id' => Auth::id(),
            'action' => 'permissions_reset_to_defaults',
            'model_type' => AdminPermission::class,
            'model_id' => null,
            'old_values' => null,
            'new_values' => $defaultPermissions,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permissions reset to defaults successfully.');
    }
}
