<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends BaseModel
{
    protected $table = 'sessions';
    protected $primaryKey = 'session_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'session_id',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];

    protected $casts = [
        'last_activity' => 'integer',
    ];

    /**
     * Get the user that owns this session
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Check if session is active
     */
    public function isActive(): bool
    {
        return $this->last_activity > (time() - 7200); // 2 hours
    }

    /**
     * Scope: Get active sessions
     */
    public function scopeActive($query)
    {
        return $query->where('last_activity', '>', time() - 7200);
    }

    /**
     * Scope: Get sessions for user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
