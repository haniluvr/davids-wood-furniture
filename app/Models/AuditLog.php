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
        return $this->belongsTo(Admin::class, 'user_id')->where('user_type', 'admin');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->where('user_type', 'user');
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
        if ($this->user_type === 'admin' && $this->user) {
            return $this->user->full_name;
        } elseif ($this->user_type === 'user' && $this->user) {
            return $this->user->first_name.' '.$this->user->last_name;
        }

        return 'Unknown User';
    }

    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'create' => 'text-green-600',
            'update' => 'text-blue-600',
            'delete' => 'text-red-600',
            'login' => 'text-purple-600',
            'logout' => 'text-gray-600',
            default => 'text-gray-600',
        };
    }
}
