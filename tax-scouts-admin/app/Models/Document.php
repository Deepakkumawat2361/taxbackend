<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'tax_return_id',
        'name',
        'original_filename',
        'file_path',
        'mime_type',
        'file_size',
        'document_type',
        'tax_year',
        'status',
        'notes',
        'rejection_reason',
        'uploaded_by',
        'reviewed_by',
        'reviewed_at',
        'is_sensitive',
        'extracted_data',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'is_sensitive' => 'boolean',
        'extracted_data' => 'array',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function taxReturn(): BelongsTo
    {
        return $this->belongsTo(TaxReturn::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(Accountant::class, 'reviewed_by');
    }

    // Accessors
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    // Scopes
    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeByTaxYear($query, $taxYear)
    {
        return $query->where('tax_year', $taxYear);
    }

    public function scopeSensitive($query)
    {
        return $query->where('is_sensitive', true);
    }
}
