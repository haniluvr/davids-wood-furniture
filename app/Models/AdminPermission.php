<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'permission',
        'granted',
    ];

    protected $casts = [
        'granted' => 'boolean',
    ];

    // Scopes
    public function scopeForRole(Builder $query, string $role): Builder
    {
        return $query->where('role', $role);
    }

    public function scopeGranted(Builder $query): Builder
    {
        return $query->where('granted', true);
    }

    public function scopeDenied(Builder $query): Builder
    {
        return $query->where('granted', false);
    }

    // Static methods
    public static function hasPermission(string $role, string $permission): bool
    {
        // Normalize role to lowercase for comparison (case-insensitive)
        $role = strtolower($role);

        $permission = static::whereRaw('LOWER(role) = ?', [$role])
            ->where('permission', $permission)
            ->first();

        return $permission ? $permission->granted : false;
    }

    public static function grantPermission(string $role, string $permission): void
    {
        static::updateOrCreate(
            ['role' => $role, 'permission' => $permission],
            ['granted' => true]
        );
    }

    public static function denyPermission(string $role, string $permission): void
    {
        static::updateOrCreate(
            ['role' => $role, 'permission' => $permission],
            ['granted' => false]
        );
    }

    public static function getRolePermissions(string $role): array
    {
        return static::where('role', $role)
            ->pluck('granted', 'permission')
            ->toArray();
    }

    public static function getAllPermissions(): array
    {
        return [
            // Products
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'products.bulk_actions',
            'products.export',

            // Orders
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',
            'orders.update_status',
            'orders.process_refund',
            'orders.export',

            // Inventory
            'inventory.view',
            'inventory.adjust',
            'inventory.export',
            'inventory.bulk_update',

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.suspend',
            'users.export',

            // Admins
            'admins.view',
            'admins.create',
            'admins.edit',
            'admins.delete',

            // Shipping
            'shipping.view',
            'shipping.create',
            'shipping.edit',
            'shipping.delete',

            // Payment Gateways
            'payment_gateways.view',
            'payment_gateways.configure',

            // CMS
            'cms.view',
            'cms.create',
            'cms.edit',
            'cms.delete',

            // Analytics
            'analytics.view',
            'analytics.export',

            // Reviews
            'reviews.view',
            'reviews.moderate',
            'reviews.delete',
            'reviews.export',

            // Settings
            'settings.view',
            'settings.edit',

            // Audit Logs
            'audit.view',

            // Notifications
            'notifications.view',
            'notifications.edit',

            // Dashboard
            'dashboard.view',
            'dashboard.analytics',
        ];
    }

    public static function getPermissionDescriptions(): array
    {
        return [
            // Products
            'products.view' => 'View product listings and details',
            'products.create' => 'Create new products',
            'products.edit' => 'Edit existing products',
            'products.delete' => 'Delete products',
            'products.bulk_actions' => 'Perform bulk actions on products',
            'products.export' => 'Export product data',

            // Orders
            'orders.view' => 'View order listings and details',
            'orders.create' => 'Create new orders manually',
            'orders.edit' => 'Edit order information',
            'orders.delete' => 'Delete orders',
            'orders.update_status' => 'Update order status',
            'orders.process_refund' => 'Process refunds for orders',
            'orders.export' => 'Export order data',

            // Inventory
            'inventory.view' => 'View inventory levels and stock',
            'inventory.adjust' => 'Adjust inventory quantities',
            'inventory.export' => 'Export inventory data',
            'inventory.bulk_update' => 'Perform bulk inventory updates',

            // Users
            'users.view' => 'View customer user accounts',
            'users.create' => 'Create new customer accounts',
            'users.edit' => 'Edit customer account information',
            'users.delete' => 'Delete customer accounts',
            'users.suspend' => 'Suspend or activate customer accounts',
            'users.export' => 'Export user data',

            // Admins
            'admins.view' => 'View admin user accounts',
            'admins.create' => 'Create new admin accounts',
            'admins.edit' => 'Edit admin account information',
            'admins.delete' => 'Delete admin accounts',

            // Shipping
            'shipping.view' => 'View shipping methods and configurations',
            'shipping.create' => 'Create new shipping methods',
            'shipping.edit' => 'Edit shipping methods',
            'shipping.delete' => 'Delete shipping methods',

            // Payment Gateways
            'payment_gateways.view' => 'View payment gateway configurations',
            'payment_gateways.configure' => 'Configure payment gateway settings',

            // CMS
            'cms.view' => 'View CMS pages and content',
            'cms.create' => 'Create new CMS pages',
            'cms.edit' => 'Edit CMS pages',
            'cms.delete' => 'Delete CMS pages',

            // Analytics
            'analytics.view' => 'View analytics and reports',
            'analytics.export' => 'Export analytics data',

            // Reviews
            'reviews.view' => 'View product reviews',
            'reviews.moderate' => 'Moderate and approve reviews',
            'reviews.delete' => 'Delete reviews',
            'reviews.export' => 'Export reviews data',

            // Settings
            'settings.view' => 'View system settings',
            'settings.edit' => 'Edit system settings',

            // Audit Logs
            'audit.view' => 'View audit trail and logs',

            // Notifications
            'notifications.view' => 'View notifications',
            'notifications.edit' => 'Manage notification settings',

            // Dashboard
            'dashboard.view' => 'View the dashboard',
            'dashboard.analytics' => 'View dashboard analytics',
        ];
    }

    public static function getDefaultRolePermissions(): array
    {
        return [
            // Super Admin - All 48 permissions
            'super_admin' => array_fill_keys(static::getAllPermissions(), true),

            // Admin - 38/48 permissions (cannot manage admins or configure payment gateways)
            'admin' => [
                // Dashboard
                'dashboard.view' => true,
                'dashboard.analytics' => true,

                // Orders
                'orders.view' => true,
                'orders.create' => true,
                'orders.edit' => true,
                'orders.update_status' => true,
                'orders.process_refund' => true,
                'orders.export' => true,
                // orders.delete => false (not granted)

                // Products
                'products.view' => true,
                'products.create' => true,
                'products.edit' => true,
                'products.delete' => true,
                'products.bulk_actions' => true,
                'products.export' => true,

                // Inventory
                'inventory.view' => true,
                'inventory.adjust' => true,
                'inventory.export' => true,
                'inventory.bulk_update' => true,

                // Users (customers)
                'users.view' => true,
                'users.edit' => true,
                'users.delete' => true,
                'users.export' => true,
                // users.create => false (not granted)
                // users.suspend => false (not granted)

                // Shipping
                'shipping.view' => true,
                'shipping.create' => true,
                'shipping.edit' => true,
                'shipping.delete' => true,

                // CMS
                'cms.view' => true,
                'cms.create' => true,
                'cms.edit' => true,
                'cms.delete' => true,

                // Analytics
                'analytics.view' => true,
                'analytics.export' => true,

                // Reviews
                'reviews.view' => true,
                'reviews.moderate' => true,
                'reviews.delete' => true,
                'reviews.export' => true,

                // Settings
                'settings.view' => true,
                'settings.edit' => true,

                // Audit Logs
                'audit.view' => true,

                // Notifications
                'notifications.view' => true,
                'notifications.edit' => true,

                // Payment Gateways (view only, cannot configure)
                'payment_gateways.view' => true,
                // payment_gateways.configure => false (not granted)

                // Admins (no access)
                // admins.* => false (not granted)
            ],

            // Sales Support Manager - 11/48 permissions
            'sales_support_manager' => [
                // Dashboard
                'dashboard.view' => true,

                // Orders (view, update status, refund, export only)
                'orders.view' => true,
                'orders.update_status' => true,
                'orders.process_refund' => true,
                'orders.export' => true,
                // orders.create => false
                // orders.edit => false
                // orders.delete => false

                // Users (customers) - view and edit only
                'users.view' => true,
                'users.edit' => true,
                // users.create => false
                // users.delete => false
                // users.suspend => false
                // users.export => false

                // Reviews - view and moderate only
                'reviews.view' => true,
                'reviews.moderate' => true,
                'reviews.export' => true,
                // reviews.delete => false

                // Audit Logs
                'audit.view' => true,

                // Notifications (for messages/respond)
                'notifications.view' => true,
                'notifications.edit' => true,
            ],

            // Inventory Fulfillment Manager - 9/48 permissions
            'inventory_fulfillment_manager' => [
                // Dashboard
                'dashboard.view' => true,

                // Inventory - full access
                'inventory.view' => true,
                'inventory.adjust' => true,
                'inventory.export' => true,
                'inventory.bulk_update' => true,

                // Orders - view and update status only
                'orders.view' => true,
                'orders.update_status' => true,
                // orders.create => false
                // orders.edit => false
                // orders.delete => false
                // orders.process_refund => false
                // orders.export => false

                // Products - view only
                'products.view' => true,
                // products.create => false
                // products.edit => false
                // products.delete => false
                // products.bulk_actions => false

                // Shipping - view and edit only
                'shipping.view' => true,
                'shipping.edit' => true,
                // shipping.create => false
                // shipping.delete => false

                // Audit Logs (use audit.view for consistency with routes)
                'audit.view' => true,
            ],

            // Product Content Manager - 10/48 permissions
            'product_content_manager' => [
                // Dashboard
                'dashboard.view' => true,

                // Products - full access
                'products.view' => true,
                'products.create' => true,
                'products.edit' => true,
                'products.delete' => true,
                'products.bulk_actions' => true,
                'products.export' => true,

                // CMS - full access
                'cms.view' => true,
                'cms.create' => true,
                'cms.edit' => true,
                'cms.delete' => true,

                // Reviews - full access
                'reviews.view' => true,
                'reviews.moderate' => true,
                'reviews.delete' => true,

                // Inventory - view only
                'inventory.view' => true,
                // inventory.adjust => false
                // inventory.export => false
                // inventory.bulk_update => false

                // Audit Logs
                'audit.view' => true,
            ],

            // Finance Reporting Analyst - 6/48 permissions (read-only analytics/reports)
            'finance_reporting_analyst' => [
                // Dashboard
                'dashboard.view' => true,

                // Analytics - view and export
                'analytics.view' => true,
                'analytics.export' => true,

                // Orders - view only
                'orders.view' => true,
                // orders.create => false
                // orders.edit => false
                // orders.delete => false
                // orders.update_status => false
                // orders.process_refund => false
                // orders.export => false

                // Users (customers) - view only
                'users.view' => true,
                // users.create => false
                // users.edit => false
                // users.delete => false
                // users.suspend => false
                // users.export => false

                // Audit Logs
                'audit.view' => true,
            ],

            // Staff - 11/48 permissions (limited access for part-time/temporary workers)
            'staff' => [
                // Dashboard
                'dashboard.view' => true,

                // Orders - view and update status only
                'orders.view' => true,
                'orders.update_status' => true,
                // orders.create => false
                // orders.edit => false
                // orders.delete => false
                // orders.process_refund => false
                // orders.export => false

                // Inventory - view and adjust only
                'inventory.view' => true,
                'inventory.adjust' => true,
                // inventory.export => false
                // inventory.bulk_update => false

                // Users (customers) - view only
                'users.view' => true,
                // users.create => false
                // users.edit => false
                // users.delete => false
                // users.suspend => false
                // users.export => false

                // Notifications (for messages/respond)
                'notifications.view' => true,
                'notifications.edit' => true,

                // Audit Logs
                'audit.view' => true,
            ],

            // Viewer - 12/48 permissions (read-only access)
            'viewer' => [
                // Dashboard
                'dashboard.view' => true,
                'dashboard.analytics' => true,

                // Orders - view only
                'orders.view' => true,

                // Products - view only
                'products.view' => true,
                'products.export' => true,

                // Inventory - view only
                'inventory.view' => true,

                // Users - view only
                'users.view' => true,

                // Shipping - view only
                'shipping.view' => true,

                // CMS - view only
                'cms.view' => true,

                // Analytics - view only (no export)
                'analytics.view' => true,

                // Reviews - view only
                'reviews.view' => true,
                'reviews.export' => true,

                // Settings - view only
                'settings.view' => true,

                // Audit Logs
                'audit.view' => true,

                // Notifications - view only
                'notifications.view' => true,
            ],
        ];
    }
}
