<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BannedUser extends BaseModel
{
    protected $table = 'banned';
    protected $primaryKey = 'banned_id';

    protected $fillable = [
        'user_id',
        'banned_who',
        'banned_theme',
        'banned_who2',
        'banned_time',
        'banned_longer',
        'banned_author',
        'banned_email',
    ];

    protected $casts = [
        'banned_time' => 'datetime',
        'banned_longer' => 'datetime',
    ];

    /**
     * Get the banned user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the admin who issued the ban
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_author', 'user_id');
    }

    /**
     * Check if ban is still active
     */
    public function isActive(): bool
    {
        if (!$this->banned_longer) {
            return true; // Permanent ban
        }

        return now()->lessThan($this->banned_longer);
    }

    /**
     * Check if ban is permanent
     */
    public function isPermanent(): bool
    {
        return $this->banned_longer === null;
    }

    /**
     * Get ban duration in days
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->banned_longer) {
            return null; // Permanent
        }

        return $this->banned_time->diffInDays($this->banned_longer);
    }

    /**
     * Scope: Get active bans
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('banned_longer')
                ->orWhere('banned_longer', '>', now());
        });
    }

    /**
     * Scope: Get expired bans
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('banned_longer')
            ->where('banned_longer', '<=', now());
    }
}
