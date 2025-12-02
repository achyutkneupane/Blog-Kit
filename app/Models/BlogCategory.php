<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

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
