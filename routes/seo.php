<?php

declare(strict_types=1);

use App\Settings\SiteSettings;

Route::get('/robots.txt', function () {
    $robotsTxt = resolve(SiteSettings::class)->robots_txt ?? '';

    return response($robotsTxt, 200)
        ->header('Content-Type', 'text/plain');
})->name('robots');

Route::get('/sitemap.txt', function () {
    $sitemapTxt = view('components.page.seo.sitemap-txt')->render();

    return response($sitemapTxt, 200)
        ->header('Content-Type', 'text/plain; charset=UTF-8');
});

Route::get('/sitemap.xml', function () {

    $xml = view('components.page.seo.sitemap')->render();

    return response($xml, 200)
        ->header('Content-Type', 'application/xml; charset=UTF-8');
});
