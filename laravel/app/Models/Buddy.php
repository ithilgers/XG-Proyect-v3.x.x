<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Buddy extends BaseModel
{
    protected $table = 'buddys';
    protected $primaryKey = 'buddy_id';

    protected $fillable = [
        'buddy_sender',
        'buddy_receiver',
        'buddy_status',
        'buddy_request_text',
        'buddy_request_time',
    ];

    protected $casts = [
        'buddy_status' => 'integer',
        'buddy_request_time' => 'datetime',
    ];

    /**
     * Get the buddy sender (requester)
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buddy_sender', 'user_id');
    }

    /**
     * Get the buddy receiver
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buddy_receiver', 'user_id');
    }

    /**
     * Check if buddy request is pending
     */
    public function isPending(): bool
    {
        return $this->buddy_status === 0;
    }

    /**
     * Check if buddy request is accepted
     */
    public function isAccepted(): bool
    {
        return $this->buddy_status === 1;
    }

    /**
     * Accept buddy request
     */
    public function accept(): void
    {
        $this->update(['buddy_status' => 1]);
    }

    /**
     * Scope: Get pending requests
     */
    public function scopePending($query)
    {
        return $query->where('buddy_status', 0);
    }

    /**
     * Scope: Get accepted buddies
     */
    public function scopeAccepted($query)
    {
        return $query->where('buddy_status', 1);
    }

    /**
     * Scope: Get buddies for user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('buddy_sender', $userId)
                ->orWhere('buddy_receiver', $userId);
        });
    }
}
