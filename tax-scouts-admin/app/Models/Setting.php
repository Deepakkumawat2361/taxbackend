<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'is_editable',
        'is_public',
        'validation_rules',
        'default_value',
        'sort_order',
    ];

    protected $casts = [
        'validation_rules' => 'array',
        'is_editable' => 'boolean',
        'is_public' => 'boolean',
    ];

    // Accessors
    public function getValueAttribute($value)
    {
        return match ($this->type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    // Mutators
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match ($this->type) {
            'boolean' => $value ? '1' : '0',
            'integer' => (string) $value,
            'json' => json_encode($value),
            default => $value,
        };
    }

    // Static methods for easy access
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): bool
    {
        $setting = static::where('key', $key)->first();
        
        if ($setting) {
            $setting->value = $value;
            return $setting->save();
        }
        
        return static::create([
            'key' => $key,
            'value' => $value,
            'type' => 'string',
            'group' => 'general',
            'label' => $key,
        ])->exists;
    }

    public static function getGroup(string $group): array
    {
        return static::where('group', $group)
                    ->orderBy('sort_order')
                    ->pluck('value', 'key')
                    ->toArray();
    }

    // Scopes
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopeEditable($query)
    {
        return $query->where('is_editable', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('group')->orderBy('sort_order');
    }
}
