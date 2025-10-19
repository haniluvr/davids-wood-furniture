<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'message',
        'status',
        'internal_notes',
        'tags',
        'assigned_to',
        'read_at',
        'responded_at',
        'responded_by',
    ];

    protected $casts = [
        'tags' => 'array',
        'read_at' => 'datetime',
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that sent the message (if authenticated)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin assigned to this message
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }

    /**
     * Get the admin who responded to this message
     */
    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'responded_by');
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        if ($this->status === 'new') {
            $this->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Mark message as responded
     */
    public function markAsResponded(int $adminId): void
    {
        $this->update([
            'status' => 'responded',
            'responded_at' => now(),
            'responded_by' => $adminId,
        ]);
    }

    /**
     * Scope to get only new messages
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope to get unread messages (new status)
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope to get responded messages
     */
    public function scopeResponded($query)
    {
        return $query->where('status', 'responded');
    }

    /**
     * Scope to get read messages
     */
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }
}
