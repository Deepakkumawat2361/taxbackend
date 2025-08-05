<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'accountant_id',
        'tax_year',
        'return_type',
        'status',
        'total_income',
        'total_expenses',
        'taxable_income',
        'tax_due',
        'tax_paid',
        'refund_due',
        'deadline',
        'submitted_date',
        'filed_date',
        'hmrc_reference',
        'income_sources',
        'deductions',
        'reliefs_claimed',
        'notes',
        'hmrc_response',
        'amendments_required',
    ];

    protected $casts = [
        'total_income' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'taxable_income' => 'decimal:2',
        'tax_due' => 'decimal:2',
        'tax_paid' => 'decimal:2',
        'refund_due' => 'decimal:2',
        'deadline' => 'date',
        'submitted_date' => 'date',
        'filed_date' => 'date',
        'income_sources' => 'array',
        'deductions' => 'array',
        'reliefs_claimed' => 'array',
        'amendments_required' => 'boolean',
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

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Accessors
    public function getIsOverdueAttribute(): bool
    {
        return $this->deadline < now() && !in_array($this->status, ['completed', 'filed']);
    }

    public function getNetTaxAttribute(): ?float
    {
        if ($this->tax_due && $this->tax_paid) {
            return $this->tax_due - $this->tax_paid;
        }
        return $this->tax_due;
    }

    // Scopes
    public function scopeByTaxYear($query, $taxYear)
    {
        return $query->where('tax_year', $taxYear);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
                    ->whereNotIn('status', ['completed', 'filed']);
    }

    public function scopeDueSoon($query, $days = 30)
    {
        return $query->where('deadline', '<=', now()->addDays($days))
                    ->where('deadline', '>=', now())
                    ->whereNotIn('status', ['completed', 'filed']);
    }
}
