<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use AchyutN\LaravelHelpers\Traits\HasTheSlug;
use App\Models\Scopes\PublishedScope;
use App\Traits\HasSEODetails;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Support\Period;
use CyrildeWit\EloquentViewable\View;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $content
 * @property int $blog_category_id
 * @property int $user_id
 * @property string|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $author
 * @property-read BlogCategory|null $category
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read SeoDetail|null $seo
 * @property-read Collection<int, View> $views
 * @property-read int|null $views_count
 *
 * @method static Builder<static>|Blog findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static Builder<static>|Blog newModelQuery()
 * @method static Builder<static>|Blog newQuery()
 * @method static Builder<static>|Blog orderByUniqueViews(string $direction = 'desc', $period = null, ?string $collection = null, string $as = 'unique_views_count')
 * @method static Builder<static>|Blog orderByViews(string $direction = 'desc', ?Period $period = null, ?string $collection = null, bool $unique = false, string $as = 'views_count')
 * @method static Builder<static>|Blog query()
 * @method static Builder<static>|Blog whereBlogCategoryId($value)
 * @method static Builder<static>|Blog whereContent($value)
 * @method static Builder<static>|Blog whereCreatedAt($value)
 * @method static Builder<static>|Blog whereDescription($value)
 * @method static Builder<static>|Blog whereId($value)
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
class Blog extends MediaModel implements Viewable
{
    use HasSEODetails;
    use HasTheSlug;
    use InteractsWithViews;

    /** @return BelongsTo<BlogCategory> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /** @return BelongsTo<User> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }
}
