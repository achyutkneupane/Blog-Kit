<?php

declare(strict_types=1);

namespace App\Schemas;

use AchyutN\LaravelSEO\Contracts\HasMarkup;
use RalphJSmit\Laravel\SEO\SchemaCollection;

trait BlogSchema
{
    public function buildSchema(SchemaCollection $schema): SchemaCollection
    {
        /** @var HasMarkup $this */
        $resolvedSEO = $this->resolveSEO();

        return $schema->add(fn (): array => array_filter([
            '@context' => 'https://schema.org',
            '@type' => $resolvedSEO->pageType ?? $this->blogSchemaType(),
            'headline' => $resolvedSEO->title,
            'description' => $resolvedSEO->description,
            'url' => $resolvedSEO->url,
            '@id' => $resolvedSEO->url,
            'image' => $resolvedSEO->image,
            'datePublished' => $resolvedSEO->publishedAt?->toIso8601String(),
            'dateModified' => $resolvedSEO->modifiedAt?->toIso8601String(),
            'author' => $resolvedSEO->authorArray()[0] ?? null,
            'publisher' => $resolvedSEO->publisherArray()[0] ?? null,
            'articleSection' => $resolvedSEO->category,
            'keywords' => $resolvedSEO->tags === [] ? null : implode(', ', $resolvedSEO->tags),
            'inLanguage' => 'en',
            'mainEntityOfPage' => $resolvedSEO->url,
        ], static fn (mixed $value): bool => $value !== null && $value !== '' && $value !== []));
    }

    protected function blogSchemaType(): string
    {
        return 'BlogPosting';
    }
}
