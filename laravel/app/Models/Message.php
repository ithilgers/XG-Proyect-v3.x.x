<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends BaseModel
{
    protected $table = 'messages';
    protected $primaryKey = 'message_id';

    protected $fillable = [
        'message_sender',
        'message_receiver',
        'message_subject',
        'message_text',
        'message_time',
        'message_type',
        'message_read',
    ];

    protected $casts = [
        'message_time' => 'datetime',
        'message_type' => 'integer',
        'message_read' => 'boolean',
    ];

    /**
     * Get the message sender
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'message_sender', 'user_id');
    }

    /**
     * Get the message receiver
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'message_receiver', 'user_id');
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        if (!$this->message_read) {
            $this->update(['message_read' => true]);
        }
    }

    /**
     * Scope: Get unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('message_read', false);
    }

    /**
     * Scope: Get inbox for user
     */
    public function scopeInbox($query, int $userId)
    {
        return $query->where('message_receiver', $userId)
            ->orderBy('message_time', 'desc');
    }

    /**
     * Scope: Get sent messages for user
     */
    public function scopeSent($query, int $userId)
    {
        return $query->where('message_sender', $userId)
            ->orderBy('message_time', 'desc');
    }

    /**
     * Scope: Search messages (Full-Text Search)
     */
    public function scopeSearch($query, string $searchTerm)
    {
        return $query->whereRaw(
            "message_search_vector @@ plainto_tsquery('english', ?)",
            [$searchTerm]
        );
    }
}
