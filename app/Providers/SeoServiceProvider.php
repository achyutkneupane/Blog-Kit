<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Controllers\RobotsController;
use App\Settings\SocialMediaSettings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use RalphJSmit\Laravel\SEO\Facades\SEOManager;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Support\TwitterCardTag;
use RalphJSmit\Laravel\SEO\TagCollection;

final class SeoServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::get('/robots.txt', RobotsController::class)->name('robots');

        SEOManager::SEODataTransformer(function (SEOData $data): SEOData {
            $handle = $this->twitterHandle();

            if (filled($handle) && in_array($data->twitter_username, [null, '@'], true)) {
                $data->twitter_username = $handle;
            }

            return $data;
        });

        SEOManager::tagTransformer(function (TagCollection $tags): TagCollection {
            $handle = $this->twitterHandle();

            if (filled($handle)) {
                $tags->push(new TwitterCardTag('creator', $handle));
            }

            return $tags;
        });
    }

    private function twitterHandle(): ?string
    {
        $handle = rescue(fn (): ?string => app(SocialMediaSettings::class)->x, null, false);

        if (blank($handle)) {
            $handle = config('seo.twitter.@username');
        }

        if (blank($handle)) {
            return null;
        }

        return str_starts_with($handle, '@') ? $handle : '@'.$handle;
    }
}
