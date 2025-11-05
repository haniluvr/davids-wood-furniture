<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'action',
        'model',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByModel($query, $model)
    {
        return $query->where('model', $model);
    }

    public function scopeByUser($query, $userType, $userId)
    {
        return $query->where('user_type', $userType)->where('user_id', $userId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Static methods for logging
    public static function log(string $action, $user, $model = null, array $oldValues = [], array $newValues = [], ?string $description = null): self
    {
        return self::create([
            'user_type' => $user instanceof Admin ? 'admin' : 'user',
            'user_id' => $user->id,
            'action' => $action,
            'model' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
        ]);
    }

    public static function logLogin($user): self
    {
        return self::log('login', $user, null, [], [], 'User logged in');
    }

    public static function logLogout($user): self
    {
        return self::log('logout', $user, null, [], [], 'User logged out');
    }

    public static function logCreate($user, $model): self
    {
        return self::log('create', $user, $model, [], $model->toArray(), "Created {$model->getTable()} record");
    }

    public static function logUpdate($user, $model, array $oldValues): self
    {
        return self::log('update', $user, $model, $oldValues, $model->toArray(), "Updated {$model->getTable()} record");
    }

    public static function logDelete($user, $model): self
    {
        return self::log('delete', $user, $model, $model->toArray(), [], "Deleted {$model->getTable()} record");
    }

    // Accessors
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('M d, Y H:i:s');
    }

    public function getUserNameAttribute(): string
    {
        if ($this->user_type === 'admin' && $this->admin) {
            return $this->admin->full_name;
        } elseif ($this->user_type === 'user' && $this->user) {
            $firstName = $this->user->first_name ?? '';
            $lastName = $this->user->last_name ?? '';
            $fullName = trim($firstName.' '.$lastName);

            return $fullName ?: 'Unknown User';
        }

        return 'Unknown User';
    }

    public function getActionColorAttribute(): string
    {
        $action = $this->action ?? '';

        return match ($action) {
            'create', 'product.created', 'order.created', 'customer.created', 'admin_user.created' => 'text-green-600',
            'update', 'product.updated', 'order.status_updated', 'customer.updated' => 'text-blue-600',
            'delete', 'product.deleted', 'order.deleted', 'customer.deleted' => 'text-red-600',
            'login' => 'text-purple-600',
            'logout' => 'text-gray-600',
            'inventory.adjusted', 'low_stock_alert.acknowledged' => 'text-orange-600',
            'order.refund_issued' => 'text-yellow-600',
            'admin_user.role_changed', 'admin_user.deactivated' => 'text-red-600',
            default => 'text-gray-600',
        };
    }

    public function getActionBadgeColorAttribute(): string
    {
        $action = $this->action ?? '';

        return match ($action) {
            'create', 'product.created', 'order.created', 'customer.created', 'admin_user.created' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'update', 'product.updated', 'order.status_updated', 'customer.updated' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'delete', 'product.deleted', 'order.deleted', 'customer.deleted' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'login' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            'logout' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
            'inventory.adjusted', 'low_stock_alert.acknowledged' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            'order.refund_issued' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'admin_user.role_changed', 'admin_user.deactivated' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
    }

    public function getCriticalityAttribute(): string
    {
        $action = $this->action ?? '';
        $criticalActions = ['delete', 'product.deleted', 'order.deleted', 'customer.deleted', 'admin_user.role_changed', 'admin_user.deactivated', 'order.refund_issued'];
        $highActions = ['product.updated', 'order.status_updated', 'inventory.adjusted', 'password_changed'];

        if (in_array($action, $criticalActions)) {
            return 'high';
        } elseif (in_array($action, $highActions)) {
            return 'medium';
        }

        return 'low';
    }

    public function getUserDisplayAttribute(): string
    {
        if ($this->user_type === 'admin' && $this->admin) {
            $role = $this->admin->role ? ' ('.ucfirst(str_replace('_', ' ', $this->admin->role)).')' : '';

            return $this->admin->full_name.$role;
        } elseif ($this->user_type === 'user' && $this->user) {
            $firstName = $this->user->first_name ?? '';
            $lastName = $this->user->last_name ?? '';
            $fullName = trim($firstName.' '.$lastName);

            return $fullName ?: 'Unknown User';
        }

        return 'System';
    }

    public function getUserEmailAttribute(): ?string
    {
        if ($this->user_type === 'admin' && $this->admin) {
            return $this->admin->email;
        } elseif ($this->user_type === 'user' && $this->user) {
            return $this->user->email ?? null;
        }

        return null;
    }

    public function getFormattedChangesAttribute(): ?string
    {
        if (empty($this->old_values) && empty($this->new_values)) {
            return null;
        }

        $changes = [];

        // Helper function to convert value to string
        $toString = function ($value) {
            if (is_array($value)) {
                return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            if (is_object($value)) {
                return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            if (is_null($value)) {
                return 'null';
            }
            if (is_bool($value)) {
                return $value ? 'true' : 'false';
            }

            return (string) $value;
        };

        if (! empty($this->old_values) && ! empty($this->new_values)) {
            foreach ($this->new_values as $key => $newValue) {
                $oldValue = $this->old_values[$key] ?? null;
                if ($oldValue !== $newValue && ! in_array($key, ['updated_at', 'created_at'])) {
                    $oldValueStr = $toString($oldValue);
                    $newValueStr = $toString($newValue);
                    $changes[] = "{$key}: ".($oldValueStr ?: 'empty').' → '.($newValueStr ?: 'empty');
                }
            }
        } elseif (! empty($this->new_values)) {
            foreach ($this->new_values as $key => $value) {
                if (! in_array($key, ['updated_at', 'created_at'])) {
                    $valueStr = $toString($value);
                    $changes[] = "{$key}: ".($valueStr ?: 'empty');
                }
            }
        }

        return ! empty($changes) ? implode(', ', array_slice($changes, 0, 5)) : null;
    }
}
