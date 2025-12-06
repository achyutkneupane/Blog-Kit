<?php

declare(strict_types=1);

use App\Settings\SiteSettings;

Route::get('/robots.txt', function () {
    $robotsTxt = resolve(SiteSettings::class)->robots_txt ?? '';

    return response($robotsTxt, 200)
        ->header('Content-Type', 'text/plain');
})->name('robots');
