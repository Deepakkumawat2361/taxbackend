<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'detailed_description',
        'category',
        'base_price',
        'premium_price',
        'features',
        'suitable_for',
        'estimated_duration_hours',
        'requires_consultation',
        'is_active',
        'is_featured',
        'icon',
        'banner_image',
        'sort_order',
        'required_documents',
        'process_steps',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'premium_price' => 'decimal:2',
        'features' => 'array',
        'suitable_for' => 'array',
        'requires_consultation' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'required_documents' => 'array',
    ];

    // Relationships
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class)
                    ->withPivot([
                        'status',
                        'agreed_price',
                        'start_date',
                        'completion_date',
                        'deadline',
                        'special_requirements',
                        'notes',
                        'progress_percentage'
                    ])
                    ->withTimestamps();
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeForClientType($query, $clientType)
    {
        return $query->whereJsonContains('suitable_for', $clientType);
    }

    public function scopeInPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('base_price', [$minPrice, $maxPrice]);
    }
}
