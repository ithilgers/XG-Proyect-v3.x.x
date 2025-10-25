<?php

declare(strict_types=1);

namespace App\Models;

class Changelog extends BaseModel
{
    protected $table = 'changelog';
    protected $primaryKey = 'changelog_id';

    protected $fillable = [
        'changelog_version',
        'changelog_title',
        'changelog_date',
        'changelog_text',
        'changelog_lang_id',
    ];

    protected $casts = [
        'changelog_date' => 'datetime',
        'changelog_lang_id' => 'integer',
    ];

    /**
     * Scope: Get latest changelog
     */
    public function scopeLatest($query, int $limit = 10)
    {
        return $query->orderBy('changelog_date', 'desc')
            ->limit($limit);
    }

    /**
     * Scope: Get by version
     */
    public function scopeVersion($query, string $version)
    {
        return $query->where('changelog_version', $version);
    }

    /**
     * Scope: Get by language
     */
    public function scopeByLanguage($query, int $langId)
    {
        return $query->where('changelog_lang_id', $langId);
    }

    /**
     * Scope: Search changelog (Full-Text Search)
     */
    public function scopeSearch($query, string $searchTerm)
    {
        return $query->whereRaw(
            "changelog_search_vector @@ plainto_tsquery('english', ?)",
            [$searchTerm]
        );
    }
}
