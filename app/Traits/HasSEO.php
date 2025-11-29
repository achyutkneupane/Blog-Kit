<?php

namespace App\Traits;

use App\Models\SeoDetail;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSEO
{
    public static function boot(): void
    {
        parent::boot();

        static::created(function ($model) {
            $model->seo()->create([
                'meta_title' => $model->title,
                'og_title' => $model->title,
                'author' => auth()->check() ? auth()->user()->name : config('app.name'),
                'robots' => ['index', 'follow'],
                'publisher' => config('app.name'),
            ]);
        });
    }

    /**
     * @return MorphOne<SeoDetail>
     */
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoDetail::class, 'seoable');
    }
}
