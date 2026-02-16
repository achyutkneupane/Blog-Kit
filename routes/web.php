<?php

declare(strict_types=1);

use App\Http\Controllers\RenderBlogView;
use App\Http\Controllers\RenderLandingPage;
use App\Http\Controllers\RenderPageView;
use App\Livewire\ListBlogs;

Route::get('/', RenderLandingPage::class)
    ->name('landing-page');
Route::get('/page/{staticPage}', RenderPageView::class)
    ->name('page.view');

Route::group([
    'prefix' => '/blog',
    'as' => 'blog.',
], function (): void {
    Route::livewire('/', ListBlogs::class)
        ->name('index');
    Route::get('/{blog}', RenderBlogView::class)
        ->name('view');
});
