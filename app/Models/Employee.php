<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'avatar',
        'role',
        'department',
        'position',
        'hire_date',
        'salary',
        'employment_status',
        'permissions',
        'status',
        'last_login_at',
        'last_login_ip',
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
    ];

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/'.$this->avatar);
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

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $permissions = $this->permissions ?? [];

        return in_array($permission, $permissions);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->employment_status === 'active';
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('employment_status', 'active');
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
        return $query->where('employment_status', $status);
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
}
