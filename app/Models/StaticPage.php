<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Traits\HasTheSlug;
use App\Traits\HasSEODetails;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use RalphJSmit\Laravel\SEO\Models\SEO;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SEO $seo
 * @property-read SeoDetail|null $seoDetails
 * @property-read string $url
 *
 * @method static Builder<static>|StaticPage findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static Builder<static>|StaticPage newModelQuery()
 * @method static Builder<static>|StaticPage newQuery()
 * @method static Builder<static>|StaticPage query()
 * @method static Builder<static>|StaticPage whereContent($value)
 * @method static Builder<static>|StaticPage whereCreatedAt($value)
 * @method static Builder<static>|StaticPage whereId($value)
 * @method static Builder<static>|StaticPage whereSlug($value)
 * @method static Builder<static>|StaticPage whereTitle($value)
 * @method static Builder<static>|StaticPage whereUpdatedAt($value)
 * @method static Builder<static>|StaticPage withUniqueSlugConstraints(Model $model, string $attribute, array $config, string $slug)
 *
 * @mixin \Eloquent
 */
final class StaticPage extends Model
{
    use HasSEODetails;
    use HasTheSlug;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn (): string => route('page.view', $this),
        );
    }
}
