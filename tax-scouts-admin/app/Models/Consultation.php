<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'accountant_id',
        'service_id',
        'type',
        'method',
        'status',
        'scheduled_at',
        'started_at',
        'ended_at',
        'duration_minutes',
        'agenda',
        'notes',
        'action_items',
        'client_questions',
        'documents_discussed',
        'fee',
        'follow_up_required',
        'next_consultation_date',
        'client_satisfaction',
        'client_feedback',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'documents_discussed' => 'array',
        'fee' => 'decimal:2',
        'follow_up_required' => 'boolean',
        'next_consultation_date' => 'date',
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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function taxAdvice(): HasMany
    {
        return $this->hasMany(TaxAdvice::class);
    }

    // Accessors
    public function getActualDurationAttribute(): ?int
    {
        if ($this->started_at && $this->ended_at) {
            return $this->started_at->diffInMinutes($this->ended_at);
        }
        return $this->duration_minutes;
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->scheduled_at > now() && $this->status === 'scheduled';
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->scheduled_at < now() && $this->status === 'scheduled';
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '>', now());
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '<', now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('scheduled_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }
}
