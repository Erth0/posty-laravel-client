<?php

namespace Mukja\Posty\Models;

use Mukja\Posty\Models\Tag;
use Mukja\Posty\Models\Topic;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory,
        HasSlug;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posty_articles';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be casted.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'reading_duration'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the tags relationship.
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'posty_articles_tags',
            'article_id',
            'tag_id'
        );
    }

    /**
     * Get the topics relationship.
     *
     * @return BelongsToMany
     */
    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(
            Topic::class,
            'posty_articles_topics',
            'article_id',
            'topic_id'
        );
    }

    /**
     * Reading time
     *
     * @return int
     */
    public function getReadingDurationAttribute(): int
    {
        $totalWords = str_word_count($this->body);
        $minutesToRead = round($totalWords / 200);

        return (int) max(1, $minutesToRead);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $article) {
            $article->tags()->detach();
            $article->topics()->detach();
        });
    }
}
