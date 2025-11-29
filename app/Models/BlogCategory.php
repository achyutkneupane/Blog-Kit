<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Traits\HasTheSlug;
use App\Traits\HasSEODetails;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Blog> $blogs
 * @property-read int|null $blogs_count
 * @property-read SeoDetail|null $seo
 *
 * @method static Builder<static>|BlogCategory findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static Builder<static>|BlogCategory newModelQuery()
 * @method static Builder<static>|BlogCategory newQuery()
 * @method static Builder<static>|BlogCategory query()
 * @method static Builder<static>|BlogCategory whereCreatedAt($value)
 * @method static Builder<static>|BlogCategory whereId($value)
 * @method static Builder<static>|BlogCategory whereName($value)
 * @method static Builder<static>|BlogCategory whereSlug($value)
 * @method static Builder<static>|BlogCategory whereUpdatedAt($value)
 * @method static Builder<static>|BlogCategory withUniqueSlugConstraints(Model $model, string $attribute, array $config, string $slug)
 *
 * @mixin \Eloquent
 */
class BlogCategory extends Model
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

    /** @return HasMany<Blog> */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }
}
