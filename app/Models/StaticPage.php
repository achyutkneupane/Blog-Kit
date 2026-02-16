<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Traits\HasTheSlug;
use AchyutN\LaravelSEO\Contracts\HasMarkup;
use AchyutN\LaravelSEO\Data\Breadcrumb;
use AchyutN\LaravelSEO\Schemas\PageSchema;
use AchyutN\LaravelSEO\Traits\InteractsWithSEO;
use App\Enums\PageType;
use App\Traits\HasSEODetails;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
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
final class StaticPage extends Model implements Viewable, HasMarkup
{
    use InteractsWithSEO;
    use HasTheSlug;
    use InteractsWithViews;
    use PageSchema;

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



    public function categoryValue(): string
    {
        return $this->type->getLabel();
    }

    public function authorValue(): ?string
    {
        /** @phpstan-var string|null */
        return config('app.name');
    }

    public function authorUrlValue(): string
    {
        return route('landing-page');
    }

    public function publisherValue(): ?string
    {
        return $this->getAuthorValue();
    }

    public function publisherUrlValue(): ?string
    {
        return $this->getAuthorUrlValue();
    }

    public function urlValue(): ?string
    {
        if ($this->type === PageType::LandingPage) {
            return route('landing-page');
        }

        if ($this->type === PageType::IndexPage) {
            return route(sprintf('%s.index', $this->name));
        }

        return null;
    }

    /** @return array<Breadcrumb> */
    public function breadcrumbs(): array
    {
        if ($this->type === PageType::LandingPage) {
            return [
                new Breadcrumb($this->getTitleValue(), $this->getURLValue()),
            ];
        }

        return [
            new Breadcrumb('Home', route('landing-page')),
            new Breadcrumb($this->getTitleValue(), $this->getURLValue()),
        ];
    }

    protected function casts(): array
    {
        return [
            'type' => PageType::class,
            'tags' => 'array'
        ];
    }
}
