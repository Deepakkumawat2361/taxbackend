<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'national_insurance_number',
        'utr_number',
        'address',
        'city',
        'postcode',
        'country',
        'client_type',
        'status',
        'annual_income',
        'tax_years',
        'notes',
        'assigned_accountant_id',
        'last_contact',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'annual_income' => 'decimal:2',
        'tax_years' => 'array',
        'last_contact' => 'datetime',
    ];

    // Relationships
    public function assignedAccountant(): BelongsTo
    {
        return $this->belongsTo(Accountant::class, 'assigned_accountant_id');
    }

    public function taxReturns(): HasMany
    {
        return $this->hasMany(TaxReturn::class);
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function taxAdvice(): HasMany
    {
        return $this->hasMany(TaxAdvice::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)
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

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('client_type', $type);
    }

    public function scopeWithAccountant($query)
    {
        return $query->whereNotNull('assigned_accountant_id');
    }
}
