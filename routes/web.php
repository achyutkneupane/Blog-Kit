<?php

declare(strict_types=1);

use App\Http\Controllers\BlogMarkdownController;
use App\Http\Controllers\CategoryMarkdownController;

Route::livewire('/', 'pages::landing-page')->name('landing-page');
Route::livewire('/page/{staticPage}', 'pages::page-view')->name('page.view');
Route::livewire('/author/{user}', 'pages::author-view')->name('author.view');
Route::livewire('/faq', 'pages::faq-view')->name('faq.view');
Route::livewire('/contact', 'pages::contact-view')->name('contact.view');
Route::get('/category/{category}.md', CategoryMarkdownController::class)->name('category.markdown');
Route::livewire('/category/{category}', 'pages::category-view')->name('category.view');

Route::group([
    'prefix' => '/blog',
    'as' => 'blog.',
], function (): void {
    Route::get('/{blog}.md', BlogMarkdownController::class)->name('markdown');
    Route::livewire('/', 'pages::list-blogs')->name('index');
    Route::livewire('/{blog}', 'pages::blog-view')->name('view');
});
