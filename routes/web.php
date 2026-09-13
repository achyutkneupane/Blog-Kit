<?php

declare(strict_types=1);

Route::livewire('/', 'pages::landing-page')->name('landing-page');
Route::livewire('/page/{staticPage}', 'pages::page-view')->name('page.view');

Route::group([
    'prefix' => '/blog',
    'as' => 'blog.',
], function (): void {
    Route::livewire('/', 'pages::list-blogs')->name('index');
    Route::livewire('/{blog}', 'pages::blog-view')->name('view');
});
