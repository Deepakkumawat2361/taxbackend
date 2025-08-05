<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'accountant_id',
        'service_id',
        'tax_return_id',
        'overall_rating',
        'communication_rating',
        'expertise_rating',
        'timeliness_rating',
        'value_rating',
        'review_text',
        'positive_feedback',
        'improvement_suggestions',
        'would_recommend',
        'is_published',
        'is_featured',
        'status',
        'admin_notes',
        'moderated_by',
        'moderated_at',
    ];

    protected $casts = [
        'would_recommend' => 'boolean',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'moderated_at' => 'datetime',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function accountant(): BelongsTo
    {
        return $this->belongsTo(Accountant::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function taxReturn(): BelongsTo
    {
        return $this->belongsTo(TaxReturn::class);
    }

    public function moderatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    // Accessors
    public function getAverageRatingAttribute(): float
    {
        $ratings = array_filter([
            $this->communication_rating,
            $this->expertise_rating,
            $this->timeliness_rating,
            $this->value_rating,
        ]);

        return count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : $this->overall_rating;
    }

    public function getRatingStarsAttribute(): string
    {
        return str_repeat('★', $this->overall_rating) . str_repeat('☆', 5 - $this->overall_rating);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeHighRated($query, $minRating = 4)
    {
        return $query->where('overall_rating', '>=', $minRating);
    }

    public function scopeRecommended($query)
    {
        return $query->where('would_recommend', true);
    }

    public function scopeForAccountant($query, $accountantId)
    {
        return $query->where('accountant_id', $accountantId);
    }

    public function scopeForService($query, $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }
}
