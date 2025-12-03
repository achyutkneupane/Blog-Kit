<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Traits\HasTheSlug;
use App\Traits\HasSEODetails;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read BlogCategory|null $pivot
 * @property-read Collection<int, Blog> $blogs
 * @property-read int|null $blogs_count
 * @property-read SeoDetail|null $seo
 *
 * @method static Builder<static>|Category findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static Builder<static>|Category newModelQuery()
 * @method static Builder<static>|Category newQuery()
 * @method static Builder<static>|Category query()
 * @method static Builder<static>|Category whereCreatedAt($value)
 * @method static Builder<static>|Category whereId($value)
 * @method static Builder<static>|Category whereName($value)
 * @method static Builder<static>|Category whereSlug($value)
 * @method static Builder<static>|Category whereUpdatedAt($value)
 * @method static Builder<static>|Category withUniqueSlugConstraints(Model $model, string $attribute, array $config, string $slug)
 *
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasSEODetails;
    use HasTheSlug;

    public string $sluggableColumn = 'name';

    /**
     * The model binding column.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** @return BelongsToMany<Blog> */
    public function blogs(): BelongsToMany
    {
        return $this->belongsToMany(Blog::class)
            ->using(BlogCategory::class)
            ->withTimestamps();
    }
}
