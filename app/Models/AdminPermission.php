<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
        $permission = static::where('role', $role)
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

            // Coupons
            'coupons.view',
            'coupons.create',
            'coupons.edit',
            'coupons.delete',

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

            // Settings
            'settings.view',
            'settings.edit',

            // Audit Logs
            'audit_logs.view',

            // Notifications
            'notifications.view',
            'notifications.edit',
        ];
    }

    public static function getDefaultRolePermissions(): array
    {
        return [
            'super_admin' => array_fill_keys(static::getAllPermissions(), true),
            'admin' => [
                'products.view' => true,
                'products.create' => true,
                'products.edit' => true,
                'products.delete' => true,
                'products.bulk_actions' => true,
                'orders.view' => true,
                'orders.create' => true,
                'orders.edit' => true,
                'orders.update_status' => true,
                'orders.process_refund' => true,
                'orders.export' => true,
                'inventory.view' => true,
                'inventory.adjust' => true,
                'inventory.export' => true,
                'users.view' => true,
                'users.create' => true,
                'users.edit' => true,
                'users.suspend' => true,
                'coupons.view' => true,
                'coupons.create' => true,
                'coupons.edit' => true,
                'coupons.delete' => true,
                'shipping.view' => true,
                'shipping.create' => true,
                'shipping.edit' => true,
                'shipping.delete' => true,
                'payment_gateways.view' => true,
                'payment_gateways.configure' => true,
                'cms.view' => true,
                'cms.create' => true,
                'cms.edit' => true,
                'cms.delete' => true,
                'analytics.view' => true,
                'analytics.export' => true,
                'reviews.view' => true,
                'reviews.moderate' => true,
                'reviews.delete' => true,
                'settings.view' => true,
                'settings.edit' => true,
                'audit_logs.view' => true,
                'notifications.view' => true,
                'notifications.edit' => true,
            ],
            'manager' => [
                'products.view' => true,
                'products.create' => true,
                'products.edit' => true,
                'orders.view' => true,
                'orders.edit' => true,
                'orders.update_status' => true,
                'inventory.view' => true,
                'inventory.adjust' => true,
                'users.view' => true,
                'users.edit' => true,
                'coupons.view' => true,
                'coupons.create' => true,
                'coupons.edit' => true,
                'shipping.view' => true,
                'shipping.edit' => true,
                'analytics.view' => true,
                'reviews.view' => true,
                'reviews.moderate' => true,
                'settings.view' => true,
                'notifications.view' => true,
            ],
            'staff' => [
                'products.view' => true,
                'orders.view' => true,
                'orders.edit' => true,
                'orders.update_status' => true,
                'inventory.view' => true,
                'users.view' => true,
                'coupons.view' => true,
                'shipping.view' => true,
                'analytics.view' => true,
                'reviews.view' => true,
                'reviews.moderate' => true,
            ],
        ];
    }
}
