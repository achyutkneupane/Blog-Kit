<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\SeoDetail;
use App\Settings\SiteSettings;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Schema\ArticleSchema;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

trait HasSEODetails
{
    use HasSEO;

    public static function boot(): void
    {
        parent::boot();

        static::created(function ($model): void {
            $siteSettings = resolve(SiteSettings::class);

            $model->seoDetails()->create([
                'meta_title' => $model->title ?? $model->name,
                'og_title' => $model->title ?? $model->name,
                'meta_description' => $model->description ?? null,
                'og_description' => $model->description ?? null,
                'author' => auth()->check() ? auth()->user()->name : $siteSettings->name,
                'robots' => ['index', 'follow'],
                'publisher' => $siteSettings->name,
            ]);
        });
    }

    /**
     * @return MorphOne<SeoDetail>
     */
    public function seoDetails(): MorphOne
    {
        return $this->morphOne(SeoDetail::class, 'seoable');
    }

    public function getDynamicSEOData(): SEOData
    {
        $siteSettings = resolve(SiteSettings::class);
        $seo = $this->seoDetails;

        $og_image = $siteSettings->og_image;
        $og_image_link = $og_image ? 'storage/'.$og_image : null;

        return new SEOData(
            title: sprintf('%s | %s', $seo->meta_title ?? $this->title, $siteSettings->name),
            description: $seo->meta_description ?? $this->description,
            author: $seo->author ?? $seo->publisher ?? $siteSettings->name,
            image: $seo->og_image ? '/storage/'.$seo->og_image : $og_image_link,
            url: $seo->canonical ?? $this->url,
            section: $this->categories ? implode(', ', $this->categories->pluck('name')->toArray()) : 'General',
            tags: $seo->meta_keywords,
            schema: SchemaCollection::make()
                ->add(fn (): array => [
                    '@context' => 'https://schema.org',
                    '@type' => 'BlogPosting',
                    'headline' => $seo->meta_title ?? $this->title,
                    'description' => $seo->og_description ?? $this->description,
                    'url' => $seo->canonical ?? $this->url,
                    'thumbnailUrl' => $seo->og_image ? '/storage/'.$seo->og_image : $og_image_link,
                    'articleSection' => $this->categories ? implode(', ', $this->categories->pluck('name')->toArray()) : 'General',
                    'datePublished' => $this->published_at ? $this->published_at->toDateString() : $this->created_at->toDateString(),
                    'inLanguage' => 'en',
                    'author' => [
                        [
                            '@type' => 'Organization',
                            'name' => $seo->publisher,
                            'url' => route('landing-page'),
                        ],
                        [
                            '@type' => 'Person',
                            'name' => $seo->author,
                            'url' => route('landing-page'),
                        ],
                    ],
                ])
                ->addArticle(function (ArticleSchema $articleSchema) use ($seo): ArticleSchema {
                    return $articleSchema->markup(function (Collection $markup) use ($seo): Collection {
                        return $markup
                            ->put('headline', $seo->meta_title ?? $this->title)
                            ->put('description', $seo->meta_description ?? $this->description)
                            ->put('url', $seo->canonical ?? $this->url)
                            ->put('datePublished', $this->published_at ? $this->published_at->toDateString() : $this->created_at->toDateString());
                    });
                }),
            type: 'article',
            robots: app()->isLocal() ? 'noindex, nofollow' : implode(', ', $seo->robots ?? ['index', 'follow']),
            openGraphTitle: $seo->og_title ?? $seo->meta_title ?? $this->title
        );
    }
}
