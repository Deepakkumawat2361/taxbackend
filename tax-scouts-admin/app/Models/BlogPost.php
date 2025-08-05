<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'gallery_images',
        'status',
        'category',
        'tags',
        'author_id',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views_count',
        'likes_count',
        'shares_count',
        'is_featured',
        'allow_comments',
        'reading_time_minutes',
        'related_services',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'tags' => 'array',
        'published_at' => 'datetime',
        'meta_keywords' => 'array',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'related_services' => 'array',
    ];

    // Relationships
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Accessors
    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    public function getExcerptOrContentAttribute(): string
    {
        return $this->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($this->content), 200);
    }

    public function getReadingTimeAttribute(): string
    {
        if ($this->reading_time_minutes) {
            return $this->reading_time_minutes . ' min read';
        }
        
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // Average reading speed
        return $minutes . ' min read';
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views_count', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }
}
