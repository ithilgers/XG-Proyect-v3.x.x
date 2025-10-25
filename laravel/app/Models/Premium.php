<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Premium extends BaseModel
{
    protected $table = 'premium';
    protected $primaryKey = 'premium_id';

    protected $fillable = [
        'user_id',
        'premium_type',
        'premium_start_time',
        'premium_end_time',
        'premium_dark_matter_cost',
    ];

    protected $casts = [
        'premium_start_time' => 'datetime',
        'premium_end_time' => 'datetime',
        'premium_dark_matter_cost' => 'integer',
    ];

    /**
     * Get the user that owns this premium feature
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Check if premium is still active
     */
    public function isActive(): bool
    {
        return now()->between($this->premium_start_time, $this->premium_end_time);
    }

    /**
     * Check if premium has expired
     */
    public function hasExpired(): bool
    {
        return now()->greaterThan($this->premium_end_time);
    }

    /**
     * Get remaining days
     */
    public function getRemainingDaysAttribute(): int
    {
        if ($this->hasExpired()) {
            return 0;
        }

        return now()->diffInDays($this->premium_end_time);
    }

    /**
     * Extend premium duration
     */
    public function extend(int $days): void
    {
        $this->update([
            'premium_end_time' => $this->premium_end_time->addDays($days),
        ]);
    }

    /**
     * Scope: Get active premium features
     */
    public function scopeActive($query)
    {
        return $query->where('premium_end_time', '>', now())
            ->where('premium_start_time', '<=', now());
    }

    /**
     * Scope: Get expired premium features
     */
    public function scopeExpired($query)
    {
        return $query->where('premium_end_time', '<=', now());
    }

    /**
     * Scope: Get by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('premium_type', $type);
    }

    /**
     * Scope: Get for user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
