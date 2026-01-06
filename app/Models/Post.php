<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Post Model
 *
 * ব্লগ পোস্ট manage করার জন্য এই model ব্যবহৃত হয়।
 * প্রতিটি post এর একজন author (User) থাকে।
 *
 * Post Status Flow:
 * draft -> pending -> published (or rejected)
 *
 * @property int $id
 * @property int $user_id - Post এর author
 * @property string $title
 * @property string $slug
 * @property string $body
 * @property string $status - draft, pending, published, rejected
 * @property string|null $rejection_reason
 * @property \Carbon\Carbon|null $published_at
 */
class Post extends Model
{
    use HasFactory;

    /**
     * Mass assignment এ কোন fields গুলো fillable
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'status',
        'rejection_reason',
        'published_at',
    ];

    /**
     * Attribute casting
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Post এর author (User)
     * প্রতিটি Post একজন User এর (Belongs-To relationship)
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helper Methods
    |--------------------------------------------------------------------------
    | Post এর বিভিন্ন status check করার জন্য helper methods
    */

    /**
     * Post কি draft অবস্থায় আছে?
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Post কি pending অবস্থায় আছে? (approval এর অপেক্ষায়)
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Post কি published?
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Post কি rejected?
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    | Eloquent Query Scopes re-usable query constraints define করতে দেয়।
    | Usage: Post::published()->get()
    */

    /**
     * শুধু published posts filter করে
     * Usage: Post::published()->get()
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * শুধু pending posts filter করে
     * Usage: Post::pending()->get()
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * শুধু draft posts filter করে
     * Usage: Post::draft()->get()
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /*
    |--------------------------------------------------------------------------
    | Model Events (Boot Method)
    |--------------------------------------------------------------------------
    | Model events allow you to hook into Eloquent lifecycle events.
    | এখানে automatically slug generate করা হচ্ছে।
    */

    /**
     * Model boot method - model initialize হওয়ার সময় চলে
     */
    protected static function boot()
    {
        parent::boot();

        // Post create হওয়ার আগে automatically slug generate করো
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
        });

        // Post update হওয়ার আগে title change হলে slug update করো
        static::updating(function ($post) {
            if ($post->isDirty('title')) {
                $post->slug = static::generateUniqueSlug($post->title, $post->id);
            }
        });
    }

    /**
     * Unique slug generate করে
     * যদি same slug exist করে, তাহলে suffix যোগ করে
     */
    protected static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Check if slug already exists
        while (static::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
