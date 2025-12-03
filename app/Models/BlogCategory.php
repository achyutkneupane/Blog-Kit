<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $blog_id
 * @property int|null $category_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Blog|null $blog
 * @property-read Category|null $category
 *
 * @method static Builder<static>|BlogCategory newModelQuery()
 * @method static Builder<static>|BlogCategory newQuery()
 * @method static Builder<static>|BlogCategory query()
 * @method static Builder<static>|BlogCategory whereBlogId($value)
 * @method static Builder<static>|BlogCategory whereCategoryId($value)
 * @method static Builder<static>|BlogCategory whereCreatedAt($value)
 * @method static Builder<static>|BlogCategory whereId($value)
 * @method static Builder<static>|BlogCategory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class BlogCategory extends Pivot
{
    /** @return BelongsTo<Blog> */
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    /** @return BelongsTo<Category> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
