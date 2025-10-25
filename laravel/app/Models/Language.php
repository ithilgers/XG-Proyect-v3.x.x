<?php

declare(strict_types=1);

namespace App\Models;

class Language extends BaseModel
{
    protected $table = 'languages';
    protected $primaryKey = 'language_id';

    protected $fillable = [
        'language_name',
        'language_directory',
        'language_default',
    ];

    protected $casts = [
        'language_default' => 'boolean',
    ];

    /**
     * Check if this is the default language
     */
    public function isDefault(): bool
    {
        return $this->language_default;
    }

    /**
     * Set as default language
     */
    public function setAsDefault(): void
    {
        // Remove default from all other languages
        static::where('language_id', '!=', $this->language_id)
            ->update(['language_default' => false]);

        // Set this as default
        $this->update(['language_default' => true]);
    }

    /**
     * Scope: Get default language
     */
    public function scopeDefault($query)
    {
        return $query->where('language_default', true)->first();
    }

    /**
     * Scope: Get by directory
     */
    public function scopeByDirectory($query, string $directory)
    {
        return $query->where('language_directory', $directory);
    }
}
