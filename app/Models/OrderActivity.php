<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OrderActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'admin_id',
        'action',
        'old_value',
        'new_value',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // Scopes
    public function scopeForOrder(Builder $query, int $orderId): Builder
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeByAdmin(Builder $query, int $adminId): Builder
    {
        return $query->where('admin_id', $adminId);
    }

    public function scopeByAction(Builder $query, string $action): Builder
    {
        return $query->where('action', $action);
    }

    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    // Static methods
    public static function logStatusChange(Order $order, string $oldStatus, string $newStatus, ?Admin $admin = null, ?string $notes = null): self
    {
        return static::create([
            'order_id' => $order->id,
            'admin_id' => $admin?->id,
            'action' => 'status_changed',
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'notes' => $notes,
            'metadata' => [
                'order_number' => $order->order_number,
                'customer_name' => $order->user?->name ?? 'Guest',
            ],
        ]);
    }

    public static function logNoteAdded(Order $order, string $notes, ?Admin $admin = null): self
    {
        return static::create([
            'order_id' => $order->id,
            'admin_id' => $admin?->id,
            'action' => 'note_added',
            'notes' => $notes,
            'metadata' => [
                'order_number' => $order->order_number,
                'customer_name' => $order->user?->name ?? 'Guest',
            ],
        ]);
    }

    public static function logRefundProcessed(Order $order, float $amount, string $reason, ?Admin $admin = null): self
    {
        return static::create([
            'order_id' => $order->id,
            'admin_id' => $admin?->id,
            'action' => 'refund_processed',
            'old_value' => $order->payment_status,
            'new_value' => 'refunded',
            'notes' => "Refund of $" . number_format($amount, 2) . " processed. Reason: " . $reason,
            'metadata' => [
                'order_number' => $order->order_number,
                'customer_name' => $order->user?->name ?? 'Guest',
                'refund_amount' => $amount,
                'refund_reason' => $reason,
            ],
        ]);
    }

    public static function logPaymentStatusChange(Order $order, string $oldStatus, string $newStatus, ?Admin $admin = null, ?string $notes = null): self
    {
        return static::create([
            'order_id' => $order->id,
            'admin_id' => $admin?->id,
            'action' => 'payment_status_changed',
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'notes' => $notes,
            'metadata' => [
                'order_number' => $order->order_number,
                'customer_name' => $order->user?->name ?? 'Guest',
            ],
        ]);
    }

    public static function logOrderCreated(Order $order, ?Admin $admin = null): self
    {
        return static::create([
            'order_id' => $order->id,
            'admin_id' => $admin?->id,
            'action' => 'order_created',
            'notes' => 'Order created' . ($admin ? ' by admin' : ' by customer'),
            'metadata' => [
                'order_number' => $order->order_number,
                'customer_name' => $order->user?->name ?? 'Guest',
                'total_amount' => $order->total_amount,
            ],
        ]);
    }

    public static function logOrderUpdated(Order $order, array $changes, ?Admin $admin = null): self
    {
        return static::create([
            'order_id' => $order->id,
            'admin_id' => $admin?->id,
            'action' => 'order_updated',
            'notes' => 'Order details updated',
            'metadata' => [
                'order_number' => $order->order_number,
                'customer_name' => $order->user?->name ?? 'Guest',
                'changes' => $changes,
            ],
        ]);
    }

    // Accessors
    public function getActionDescriptionAttribute(): string
    {
        return match($this->action) {
            'status_changed' => 'Order status changed from ' . ucfirst($this->old_value) . ' to ' . ucfirst($this->new_value),
            'payment_status_changed' => 'Payment status changed from ' . ucfirst($this->old_value) . ' to ' . ucfirst($this->new_value),
            'note_added' => 'Note added',
            'refund_processed' => 'Refund processed',
            'order_created' => 'Order created',
            'order_updated' => 'Order updated',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }

    public function getAdminNameAttribute(): string
    {
        return $this->admin ? $this->admin->first_name . ' ' . $this->admin->last_name : 'System';
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('M d, Y H:i:s');
    }
}
