<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use AchyutN\LaravelHelpers\Traits\HasTheSlug;
use AchyutN\LaravelSEO\Contracts\HasMarkup;
use AchyutN\LaravelSEO\Data\Breadcrumb;
use AchyutN\LaravelSEO\Models\SEO;
use AchyutN\LaravelSEO\Schemas\BlogSchema;
use AchyutN\LaravelSEO\Traits\InteractsWithSEO;
use App\Models\Scopes\LowerRoleOnly;
use App\Models\Scopes\PublishedScope;
use App\Traits\HasReadTime;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Support\Period;
use CyrildeWit\EloquentViewable\View;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $content
 * @property int|null $user_id
 * @property bool $is_featured
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $author
 * @property-read BlogCategory|null $pivot
 * @property-read Collection<int, Category> $categories
 * @property-read int|null $categories_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read int $minutes_read
 * @property-read string $minutes_read_text
 * @property-read SEO|null $seo
 * @property-read string $url
 * @property-read Collection<int, View> $views
 * @property-read int|null $views_count
 *
 * @method static Builder<static>|Blog findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static Builder<static>|Blog newModelQuery()
 * @method static Builder<static>|Blog newQuery()
 * @method static Builder<static>|Blog orderByUniqueViews(string $direction = 'desc', $period = null, ?string $collection = null, string $as = 'unique_views_count')
 * @method static Builder<static>|Blog orderByViews(string $direction = 'desc', ?Period $period = null, ?string $collection = null, bool $unique = false, string $as = 'views_count')
 * @method static Builder<static>|Blog query()
 * @method static Builder<static>|Blog whereContent($value)
 * @method static Builder<static>|Blog whereCreatedAt($value)
 * @method static Builder<static>|Blog whereDescription($value)
 * @method static Builder<static>|Blog whereId($value)
 * @method static Builder<static>|Blog whereIsFeatured($value)
 * @method static Builder<static>|Blog wherePublishedAt($value)
 * @method static Builder<static>|Blog whereSlug($value)
 * @method static Builder<static>|Blog whereTitle($value)
 * @method static Builder<static>|Blog whereUpdatedAt($value)
 * @method static Builder<static>|Blog whereUserId($value)
 * @method static Builder<static>|Blog withUniqueSlugConstraints(Model $model, string $attribute, array $config, string $slug)
 * @method static Builder<static>|Blog withViewsCount(?Period $period = null, ?string $collection = null, bool $unique = false, string $as = 'views_count')
 *
 * @mixin \Eloquent
 */
#[ScopedBy(PublishedScope::class)]
class Blog extends MediaModel implements HasMarkup, Viewable
{
    use BlogSchema;
    use HasReadTime;
    use HasTheSlug;
    use InteractsWithSEO;
    use InteractsWithViews;

    /** @return BelongsToMany<Category> */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
            ->using(BlogCategory::class)
            ->withTimestamps();
    }

    /** @return BelongsTo<User> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')
            ->withoutGlobalScopes([
                LowerRoleOnly::class,
            ]);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function authorValue(): ?string
    {
        /** @phpstan-var string|null */
        return $this->author?->getAttribute('name');
    }

    public function authorUrlValue(): string
    {
        return route('landing-page');
    }

    public function publisherValue(): ?string
    {
        /** @phpstan-var string|null */
        return config('app.name');
    }

    public function publisherUrlValue(): string
    {
        return route('landing-page');
    }

    public function urlValue(): ?string
    {
        return $this->url;
    }

    public function categoryValue(): ?string
    {
        return $this->categories()->first()?->getAttribute('name');
    }

    /** @return array<Breadcrumb> */
    public function breadcrumbs(): array
    {
        return [
            new Breadcrumb('Home', route('landing-page')),
            new Breadcrumb('Blogs', route('blog.index')),
            new Breadcrumb($this->getTitleValue(), $this->getURLValue()),
        ];
    }

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'tags' => 'array',
        ];
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn (): string => route('blog.view', $this),
        );
    }
}
