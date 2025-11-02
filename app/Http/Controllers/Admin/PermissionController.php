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
        $roles = [
            'super_admin',
            'admin',
            'sales_support_manager',
            'inventory_fulfillment_manager',
            'product_content_manager',
            'finance_reporting_analyst',
            'staff',
            'viewer',
        ];
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

        // Get old permissions for logging
        $oldPermissions = [];
        foreach ($permissions as $role => $rolePermissions) {
            foreach ($rolePermissions as $permission => $granted) {
                $oldPermission = AdminPermission::where('role', $role)
                    ->where('permission', $permission)
                    ->first();
                $oldPermissions[$role][$permission] = $oldPermission ? $oldPermission->granted : false;
            }
        }

        // Log the action
        AuditLog::log('admin_user.permissions_updated', Auth::guard('admin')->user(), null, $oldPermissions, $permissions, 'Updated permissions for multiple roles');

        return redirect()->to(admin_route('permissions.index'))
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
        AuditLog::log('admin_user.permissions_reset', Auth::guard('admin')->user(), null, [], $defaultPermissions, 'Reset all permissions to defaults');

        return redirect()->to(admin_route('permissions.index'))
            ->with('success', 'Permissions reset to defaults successfully.');
    }
}
