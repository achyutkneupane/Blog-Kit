<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
