<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxAdvice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'accountant_id',
        'consultation_id',
        'advice_reference',
        'category',
        'subject',
        'client_question',
        'advice_given',
        'recommendations',
        'action_items',
        'relevant_legislation',
        'supporting_documents',
        'potential_savings',
        'implementation_deadline',
        'priority',
        'status',
        'requires_follow_up',
        'follow_up_date',
        'follow_up_notes',
        'client_rating',
        'client_feedback',
    ];

    protected $casts = [
        'action_items' => 'array',
        'relevant_legislation' => 'array',
        'supporting_documents' => 'array',
        'potential_savings' => 'decimal:2',
        'implementation_deadline' => 'date',
        'requires_follow_up' => 'boolean',
        'follow_up_date' => 'date',
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

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    // Accessors
    public function getIsOverdueAttribute(): bool
    {
        return $this->implementation_deadline && 
               $this->implementation_deadline < now() && 
               $this->status !== 'implemented';
    }

    public function getNeedsFollowUpAttribute(): bool
    {
        return $this->requires_follow_up && 
               $this->follow_up_date && 
               $this->follow_up_date <= now();
    }

    // Scopes
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('implementation_deadline', '<', now())
                    ->where('status', '!=', 'implemented');
    }

    public function scopeNeedsFollowUp($query)
    {
        return $query->where('requires_follow_up', true)
                    ->where('follow_up_date', '<=', now());
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    public function scopeWithSavings($query)
    {
        return $query->whereNotNull('potential_savings')
                    ->where('potential_savings', '>', 0);
    }
}
