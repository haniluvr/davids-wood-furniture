<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'recipient_type',
        'recipient_id',
        'title',
        'message',
        'data',
        'status',
        'sent_at',
        'read_at',
        'channel',
        'error_message',
    ];

    protected $casts = [
        'data' => 'array',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function recipient(): MorphTo
    {
        return $this->morphTo('recipient', 'recipient_type', 'recipient_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeUnread($query)
    {
        return $query->whereIn('status', ['pending', 'sent']);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByRecipient($query, $recipientType, $recipientId = null)
    {
        $query->where('recipient_type', $recipientType);

        if ($recipientId) {
            $query->where('recipient_id', $recipientId);
        }

        return $query;
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'text-yellow-600 bg-yellow-100',
            'sent' => 'text-green-600 bg-green-100',
            'failed' => 'text-red-600 bg-red-100',
            'read' => 'text-blue-600 bg-blue-100',
            default => 'text-gray-600 bg-gray-100',
        };
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('M d, Y H:i');
    }

    public function getFormattedSentAtAttribute(): ?string
    {
        return $this->sent_at?->format('M d, Y H:i');
    }

    public function getFormattedReadAtAttribute(): ?string
    {
        return $this->read_at?->format('M d, Y H:i');
    }

    // Methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRead(): bool
    {
        return $this->status === 'read';
    }

    public function markAsSent(): bool
    {
        return $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function markAsFailed(?string $errorMessage = null): bool
    {
        return $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    public function markAsRead(): bool
    {
        return $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    // Static methods
    public static function createForAdmin(Admin $admin, string $title, string $message, string $type = 'system', array $data = []): self
    {
        return self::create([
            'type' => $type,
            'recipient_type' => 'admin',
            'recipient_id' => $admin->id,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function createForUser(User $user, string $title, string $message, string $type = 'system', array $data = []): self
    {
        return self::create([
            'type' => $type,
            'recipient_type' => 'user',
            'recipient_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function createForAll(string $title, string $message, string $type = 'system', array $data = []): self
    {
        return self::create([
            'type' => $type,
            'recipient_type' => 'all',
            'recipient_id' => null,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function getTypeOptions(): array
    {
        return [
            'email' => 'Email',
            'sms' => 'SMS',
            'push' => 'Push Notification',
            'system' => 'System Notification',
        ];
    }

    public static function getStatusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'sent' => 'Sent',
            'failed' => 'Failed',
            'read' => 'Read',
        ];
    }

    // Notification templates
    public static function orderCreated(Order $order): void
    {
        self::createForUser(
            $order->user,
            'Order Confirmation',
            "Your order #{$order->order_number} has been confirmed and is being processed.",
            'email',
            ['order_id' => $order->id, 'order_number' => $order->order_number]
        );
    }

    public static function orderStatusUpdated(Order $order): void
    {
        self::createForUser(
            $order->user,
            'Order Status Update',
            "Your order #{$order->order_number} status has been updated to: ".ucfirst($order->status),
            'email',
            ['order_id' => $order->id, 'order_number' => $order->order_number, 'status' => $order->status]
        );
    }

    public static function lowStockAlert(Product $product): void
    {
        // Notify all admins with inventory permissions
        $admins = Admin::active()->get()->filter(function ($admin) {
            return $admin->hasPermission('products.view');
        });

        foreach ($admins as $admin) {
            self::createForAdmin(
                $admin,
                'Low Stock Alert',
                "Product '{$product->name}' is running low on stock. Current quantity: {$product->stock_quantity}",
                'system',
                ['product_id' => $product->id, 'stock_quantity' => $product->stock_quantity]
            );
        }
    }
}
