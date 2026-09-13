<?php

declare(strict_types=1);

use App\Http\Controllers\OGImageController;

Route::get(
    sprintf('%s/{type}/{key}/{hash}.png', config('og-image.route.prefix')),
    OGImageController::class,
)
    ->where('type', '[a-z0-9-]+')
    ->where('key', '[A-Za-z0-9-_]+')
    ->where('hash', '[a-f0-9]{8}')
    ->middleware(sprintf('throttle:%d,1', config('og-image.route.throttle')))
    ->name((string) config('og-image.route.name'));

Route::livewire('/', 'pages::landing-page')->name('landing-page');
Route::livewire('/page/{staticPage}', 'pages::page-view')->name('page.view');

Route::group([
    'prefix' => '/blog',
    'as' => 'blog.',
], function (): void {
    Route::livewire('/', 'pages::list-blogs')->name('index');
    Route::livewire('/{blog}', 'pages::blog-view')->name('view');
});
