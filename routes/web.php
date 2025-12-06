<?php

declare(strict_types=1);

use App\Http\Controllers\RenderBlogView;
use App\Http\Controllers\RenderLandingPage;
use App\Http\Controllers\RenderPageView;

Route::get('/', RenderLandingPage::class)
    ->name('landing-page');
Route::get('/page/{staticPage}', RenderPageView::class)
    ->name('page.view');
Route::get('/blog/{blog}', RenderBlogView::class)
    ->name('blog.view');
