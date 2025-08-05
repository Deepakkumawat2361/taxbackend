<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'tax_return_id',
        'consultation_id',
        'payment_reference',
        'amount',
        'tax_amount',
        'total_amount',
        'currency',
        'status',
        'payment_method',
        'payment_gateway',
        'gateway_transaction_id',
        'gateway_response',
        'description',
        'paid_at',
        'refunded_at',
        'refunded_amount',
        'refund_reason',
        'invoice_number',
        'invoice_sent',
        'invoice_sent_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'refunded_amount' => 'decimal:2',
        'invoice_sent' => 'boolean',
        'invoice_sent_at' => 'datetime',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function taxReturn(): BelongsTo
    {
        return $this->belongsTo(TaxReturn::class);
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    // Accessors
    public function getNetAmountAttribute(): float
    {
        if ($this->refunded_amount) {
            return $this->total_amount - $this->refunded_amount;
        }
        return $this->total_amount;
    }

    public function getIsRefundedAttribute(): bool
    {
        return $this->status === 'refunded' && $this->refunded_amount > 0;
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed' && $this->paid_at !== null;
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeByGateway($query, $gateway)
    {
        return $query->where('payment_gateway', $gateway);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('paid_at', now()->month)
                    ->whereYear('paid_at', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('paid_at', now()->year);
    }
}
