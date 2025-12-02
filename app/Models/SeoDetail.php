<?php

declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property array<array-key, mixed>|null $meta_keywords
 * @property string|null $og_title
 * @property string|null $og_description
 * @property string|null $og_image
 * @property string|null $og_url
 * @property string|null $canonical
 * @property array<array-key, mixed>|null $robots
 * @property string|null $author
 * @property string|null $publisher
 * @property string|null $schema
 * @property string $seoable_type
 * @property int $seoable_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $seoable
 *
 * @method static Builder<static>|SeoDetail newModelQuery()
 * @method static Builder<static>|SeoDetail newQuery()
 * @method static Builder<static>|SeoDetail query()
 * @method static Builder<static>|SeoDetail whereAuthor($value)
 * @method static Builder<static>|SeoDetail whereCanonical($value)
 * @method static Builder<static>|SeoDetail whereCreatedAt($value)
 * @method static Builder<static>|SeoDetail whereId($value)
 * @method static Builder<static>|SeoDetail whereMetaDescription($value)
 * @method static Builder<static>|SeoDetail whereMetaKeywords($value)
 * @method static Builder<static>|SeoDetail whereMetaTitle($value)
 * @method static Builder<static>|SeoDetail whereOgDescription($value)
 * @method static Builder<static>|SeoDetail whereOgImage($value)
 * @method static Builder<static>|SeoDetail whereOgTitle($value)
 * @method static Builder<static>|SeoDetail whereOgUrl($value)
 * @method static Builder<static>|SeoDetail wherePublisher($value)
 * @method static Builder<static>|SeoDetail whereRobots($value)
 * @method static Builder<static>|SeoDetail whereSchema($value)
 * @method static Builder<static>|SeoDetail whereSeoableId($value)
 * @method static Builder<static>|SeoDetail whereSeoableType($value)
 * @method static Builder<static>|SeoDetail whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
final class SeoDetail extends Model
{
    public function seoable(): MorphTo
    {
        return $this->morphTo('seoable');
    }

    protected function casts(): array
    {
        return [
            'meta_keywords' => 'array',
            'robots' => 'array',
        ];
    }
}
