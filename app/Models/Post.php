<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    /**
     * Interact with the comment's title.
     * 
     * @return Attribute
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strip_tags($value),
        );
    }

    /**
     * Interact with the comment's content.
     * 
     * @return Attribute
     */
    protected function content(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strip_tags($value),
        );
    }

    /**
     * Filter blog posts by categories.
     * 
     * @return Builder
     */
    public function scopeFilterCategories($query, $categories): Builder
    {
        $categories = $categories ? array_map('intval', explode(',', $categories)) : [];

        return $query
            ->when(!empty($categories), function (Builder $query) use ($categories) {
                $query
                    ->whereHas('categories', function (Builder $query) use ($categories) {
                        $query
                            ->whereIn('categories.id', $categories);
                    });
            });
    }

    /**
     * Filter blog posts by search term.
     * 
     * @return Builder
     */
    public function scopeSearchTerm($query, $searchTerm): Builder
    {
        return $query
            ->when($searchTerm && $searchTerm !== '', function (Builder $query) use ($searchTerm) {
                $searchTerm = strtolower($searchTerm);

                $query->whereRaw("(LOWER(posts.title) LIKE ? OR LOWER(posts.content) LIKE ?)", ["%{$searchTerm}%", "%{$searchTerm}%"]);
            });
    }

    /**
     * Get the categories for the blog post.
     * 
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_category', 'post_id', 'category_id');
    }

    /**
     * Get the comments for the blog post.
     * 
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->orderBy('created_at', 'DESC');
    }

    /**
     * Get the author for the blog post.
     * 
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
