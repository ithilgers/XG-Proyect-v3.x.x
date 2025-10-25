<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends BaseModel
{
    protected $table = 'notes';
    protected $primaryKey = 'note_id';

    protected $fillable = [
        'user_id',
        'note_title',
        'note_text',
        'note_priority',
        'note_time',
    ];

    protected $casts = [
        'note_priority' => 'integer',
        'note_time' => 'datetime',
    ];

    /**
     * Get the user that owns this note
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Scope: Get high priority notes
     */
    public function scopeHighPriority($query)
    {
        return $query->where('note_priority', '>=', 3);
    }

    /**
     * Scope: Search notes (Full-Text Search)
     */
    public function scopeSearch($query, string $searchTerm)
    {
        return $query->whereRaw(
            "note_search_vector @@ plainto_tsquery('english', ?)",
            [$searchTerm]
        );
    }

    /**
     * Scope: Get recent notes
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('note_time', '>=', now()->subDays($days));
    }
}
