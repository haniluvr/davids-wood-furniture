<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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

        // Get role permissions, with defaults if not set
        $rolePermissions = [];
        $defaultPermissions = AdminPermission::getDefaultRolePermissions();

        foreach ($roles as $role) {
            $dbPermissions = AdminPermission::getRolePermissions($role);

            // If no permissions in DB, use defaults
            if (empty($dbPermissions) && isset($defaultPermissions[$role])) {
                $rolePermissions[$role] = $defaultPermissions[$role];
            } else {
                // Merge defaults with DB permissions to ensure all permissions are present
                $rolePermissions[$role] = [];
                foreach ($permissions as $permission) {
                    if (isset($dbPermissions[$permission])) {
                        $rolePermissions[$role][$permission] = $dbPermissions[$permission];
                    } elseif (isset($defaultPermissions[$role][$permission])) {
                        $rolePermissions[$role][$permission] = $defaultPermissions[$role][$permission];
                    } else {
                        $rolePermissions[$role][$permission] = false;
                    }
                }
            }
        }

        // Calculate dynamic user counts per role
        $roleUserCounts = [];
        foreach ($roles as $role) {
            $roleUserCounts[$role] = Admin::where('role', $role)->count();
        }

        // Get permission descriptions
        $permissionDescriptions = AdminPermission::getPermissionDescriptions();

        // Group permissions by category
        $permissionCategories = [];
        foreach ($permissions as $permission) {
            $category = explode('.', $permission)[0];
            if (! isset($permissionCategories[$category])) {
                $permissionCategories[$category] = [];
            }
            $permissionCategories[$category][] = $permission;
        }

        // Get default permissions for reference
        $defaultPermissions = AdminPermission::getDefaultRolePermissions();

        return view('admin.permissions.index', compact('roles', 'permissions', 'rolePermissions', 'roleUserCounts', 'permissionDescriptions', 'permissionCategories', 'defaultPermissions'));
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

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Permissions updated successfully.']);
        }

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
            foreach ($permissions as $permission => $granted) {
                // Only create if granted is true
                if ($granted === true) {
                    AdminPermission::updateOrCreate(
                        [
                            'role' => strtolower($role), // Normalize role to lowercase
                            'permission' => $permission,
                        ],
                        [
                            'granted' => true,
                        ]
                    );
                }
            }
        }

        // Log the action
        AuditLog::log('admin_user.permissions_reset', Auth::guard('admin')->user(), null, [], $defaultPermissions, 'Reset all permissions to defaults');

        return response()->json(['success' => true, 'message' => 'Permissions reset to defaults successfully.']);
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'role_key' => 'required|string|max:255|regex:/^[a-z_]+$/',
            'role_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        // Check if role already exists
        $existingRoles = [
            'super_admin', 'admin', 'sales_support_manager',
            'inventory_fulfillment_manager', 'product_content_manager',
            'finance_reporting_analyst', 'staff', 'viewer',
        ];

        if (in_array($request->role_key, $existingRoles)) {
            return response()->json(['success' => false, 'message' => 'Role already exists.'], 422);
        }

        // Log the action
        AuditLog::log('admin_user.role_created', Auth::guard('admin')->user(), null, [], [
            'role_key' => $request->role_key,
            'role_name' => $request->role_name,
            'description' => $request->description,
        ], "Created new role: {$request->role_name}");

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully.',
            'role' => [
                'key' => $request->role_key,
                'name' => $request->role_name,
                'description' => $request->description,
            ],
        ]);
    }
}
