<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'employees';

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'personal_email',
        'password',
        'phone',
        'avatar',
        'role',
        'department',
        'position',
        'hire_date',
        'salary',
        'permissions',
        'status',
        'last_login_at',
        'last_login_ip',
        'two_factor_enabled',
        'two_factor_verified_at',
        'otp_code',
        'otp_expires_at',
        'notification_preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
        'permissions' => 'array',
        'password' => 'hashed',
        'two_factor_enabled' => 'boolean',
        'two_factor_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'notification_preferences' => 'array',
    ];

    // Accessors
    public function getFullNameAttribute(): string
    {
        $firstName = $this->first_name ?? '';
        $lastName = $this->last_name ?? '';

        $fullName = trim($firstName.' '.$lastName);

        return $fullName ?: 'Unknown';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            // Use dynamic storage URL (supports both local and S3)
            return storage_url($this->avatar);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->full_name).'&color=3C50E0&background=EBF4FF';
    }

    // Role and Permission Methods
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Use AdminPermission model (same as middleware)
        return \App\Models\AdminPermission::hasPermission($this->role, $permission);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // Relationships
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id')->where('user_type', 'admin');
    }

    public function createdProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'created_by');
    }

    public function processedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'processed_by');
    }

    public function createdPages(): HasMany
    {
        return $this->hasMany(CmsPage::class, 'created_by');
    }

    public function updatedPages(): HasMany
    {
        return $this->hasMany(CmsPage::class, 'updated_by');
    }

    public function orderActivities(): HasMany
    {
        return $this->hasMany(OrderActivity::class, 'admin_id');
    }

    public function reviewResponses(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'responded_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByEmploymentStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Methods
    public function updateLastLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }

    public static function getPermissionsList(): array
    {
        return [
            'products.view' => 'View Products',
            'products.create' => 'Create Products',
            'products.edit' => 'Edit Products',
            'products.delete' => 'Delete Products',
            'orders.view' => 'View Orders',
            'orders.edit' => 'Edit Orders',
            'orders.delete' => 'Delete Orders',
            'users.view' => 'View Users',
            'users.edit' => 'Edit Users',
            'users.delete' => 'Delete Users',
            'admins.view' => 'View Admins',
            'admins.create' => 'Create Admins',
            'admins.edit' => 'Edit Admins',
            'admins.delete' => 'Delete Admins',
            'cms.view' => 'View CMS',
            'cms.create' => 'Create CMS Pages',
            'cms.edit' => 'Edit CMS Pages',
            'cms.delete' => 'Delete CMS Pages',
            'settings.view' => 'View Settings',
            'settings.edit' => 'Edit Settings',
            'analytics.view' => 'View Analytics',
            'notifications.view' => 'View Notifications',
            'notifications.send' => 'Send Notifications',
            'audit.view' => 'View Audit Logs',
        ];
    }

    /**
     * Generate OTP code for admin 2FA.
     */
    public function generateOtpCode()
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->update([
            'otp_code' => $code,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        return $code;
    }

    /**
     * Verify OTP code for admin 2FA.
     */
    public function verifyOtpCode($code)
    {
        if (! $this->otp_code || ! $this->otp_expires_at) {
            return false;
        }

        if ($this->otp_expires_at->isPast()) {
            return false;
        }

        if ($this->otp_code !== $code) {
            return false;
        }

        // Clear the code after successful verification
        $this->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        return true;
    }

    /**
     * Check if admin wants to receive a specific notification type.
     */
    public function wantsNotification(string $type): bool
    {
        $preferences = $this->notification_preferences ?? [];

        // Default to true if preferences not set
        if (empty($preferences)) {
            return true;
        }

        return $preferences[$type] ?? true;
    }

    /**
     * Get default notification preferences.
     */
    public static function getDefaultNotificationPreferences(): array
    {
        return [
            'new_orders' => true,
            'order_status_updates' => true,
            'customer_messages' => true,
            'low_stock' => true,
            'new_customers' => false,
            'product_reviews' => true,
            'refund_requests' => true,
        ];
    }
}
