<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Acs extends BaseModel
{
    protected $table = 'acs';
    protected $primaryKey = 'acs_id';

    protected $fillable = [
        'acs_name',
        'acs_owner',
        'acs_galaxy',
        'acs_system',
        'acs_planet',
        'acs_planet_type',
        'acs_members',
        'acs_invited',
        'acs_fleet_count',
    ];

    protected $casts = [
        'acs_galaxy' => 'integer',
        'acs_system' => 'integer',
        'acs_planet' => 'integer',
        'acs_planet_type' => 'integer',
        'acs_members' => 'array', // PostgreSQL JSONB
        'acs_invited' => 'array', // PostgreSQL JSONB
        'acs_fleet_count' => 'integer',
    ];

    /**
     * Get the ACS owner
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acs_owner', 'user_id');
    }

    /**
     * Get target coordinates as string
     */
    public function getTargetCoordinatesAttribute(): string
    {
        return "{$this->acs_galaxy}:{$this->acs_system}:{$this->acs_planet}";
    }

    /**
     * Check if user is member
     */
    public function isMember(int $userId): bool
    {
        if (!is_array($this->acs_members)) {
            return false;
        }

        return in_array($userId, $this->acs_members);
    }

    /**
     * Check if user is invited
     */
    public function isInvited(int $userId): bool
    {
        if (!is_array($this->acs_invited)) {
            return false;
        }

        return in_array($userId, $this->acs_invited);
    }

    /**
     * Add member to ACS
     */
    public function addMember(int $userId): void
    {
        $members = $this->acs_members ?? [];

        if (!in_array($userId, $members)) {
            $members[] = $userId;
            $this->update(['acs_members' => $members]);
        }
    }

    /**
     * Remove member from ACS
     */
    public function removeMember(int $userId): void
    {
        $members = $this->acs_members ?? [];
        $members = array_filter($members, fn ($id) => $id !== $userId);
        $this->update(['acs_members' => array_values($members)]);
    }

    /**
     * Get member count
     */
    public function getMemberCountAttribute(): int
    {
        return is_array($this->acs_members) ? count($this->acs_members) : 0;
    }
}
