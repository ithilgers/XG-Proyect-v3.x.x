<?php

declare(strict_types=1);

namespace App\Models;

class Option extends BaseModel
{
    protected $table = 'options';
    protected $primaryKey = 'option_id';

    protected $fillable = [
        'option_name',
        'option_value',
        'option_type',
    ];

    protected $casts = [
        // Dynamic casting based on option_type
    ];

    /**
     * Get option value with proper type casting
     */
    public function getTypedValue(): mixed
    {
        return match ($this->option_type) {
            'integer' => (int) $this->option_value,
            'boolean' => (bool) $this->option_value,
            'array', 'json' => json_decode($this->option_value, true),
            default => $this->option_value,
        };
    }

    /**
     * Set option value with proper type handling
     */
    public function setTypedValue(mixed $value): void
    {
        $this->option_value = match ($this->option_type) {
            'array', 'json' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };

        $this->save();
    }

    /**
     * Scope: Get option by name
     */
    public function scopeByName($query, string $name)
    {
        return $query->where('option_name', $name)->first();
    }

    /**
     * Scope: Get options by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('option_type', $type);
    }

    /**
     * Static helper to get option value
     */
    public static function get(string $name, mixed $default = null): mixed
    {
        $option = static::byName(null, $name);

        return $option ? $option->getTypedValue() : $default;
    }

    /**
     * Static helper to set option value
     */
    public static function set(string $name, mixed $value, string $type = 'string'): void
    {
        $option = static::byName(null, $name);

        if ($option) {
            $option->setTypedValue($value);
        } else {
            $newOption = static::create([
                'option_name' => $name,
                'option_type' => $type,
            ]);
            $newOption->setTypedValue($value);
        }
    }
}
