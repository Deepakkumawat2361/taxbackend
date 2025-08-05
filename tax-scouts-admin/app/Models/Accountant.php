<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accountant extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'qualification',
        'years_experience',
        'specializations',
        'status',
        'bio',
        'profile_photo',
        'hourly_rate',
        'max_clients',
        'current_clients',
        'rating',
        'total_reviews',
        'working_hours',
        'available_for_new_clients',
    ];

    protected $casts = [
        'specializations' => 'array',
        'hourly_rate' => 'decimal:2',
        'rating' => 'decimal:2',
        'working_hours' => 'array',
        'available_for_new_clients' => 'boolean',
    ];

    // Relationships
    public function assignedClients(): HasMany
    {
        return $this->hasMany(Client::class, 'assigned_accountant_id');
    }

    public function taxReturns(): HasMany
    {
        return $this->hasMany(TaxReturn::class);
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function taxAdvice(): HasMany
    {
        return $this->hasMany(TaxAdvice::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function reviewedDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'reviewed_by');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'active' && 
               $this->available_for_new_clients && 
               $this->current_clients < $this->max_clients;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
                    ->where('available_for_new_clients', true)
                    ->whereColumn('current_clients', '<', 'max_clients');
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->whereJsonContains('specializations', $specialization);
    }

    public function scopeHighRated($query, $minRating = 4.0)
    {
        return $query->where('rating', '>=', $minRating);
    }
}
