<?php

declare(strict_types=1);

use App\Enums\PageType;
use App\Models\Blog;
use App\Models\Faq;
use App\Models\StaticPage;

it('seeds seo friendly static pages', function (): void {
    $this->seed();

    $landing = StaticPage::query()->where('slug', 'landing-page')->firstOrFail();
    $blog = StaticPage::query()
        ->where('name', 'blog')
        ->where('type', PageType::IndexPage)
        ->firstOrFail();
    $about = StaticPage::query()->where('slug', 'about-us')->firstOrFail();

    foreach ([$landing, $blog, $about] as $page) {
        expect($page->seo?->meta_title)->toBeString()->not->toBeEmpty();

        $description = (string) $page->seo?->meta_description;

        expect(mb_strlen($description))->toBeGreaterThanOrEqual(120)
            ->and(mb_strlen($description))->toBeLessThanOrEqual(160);
    }
});

it('refreshes seo values when seeded again', function (): void {
    $this->seed();

    $landing = StaticPage::query()->where('slug', 'landing-page')->firstOrFail();
    $landing->seo()->update(['meta_title' => 'Stale title']);

    $this->seed();

    expect($landing->refresh()->seo?->meta_title)->not->toBe('Stale title');
});

it('refreshes static page descriptions with current copy', function (): void {
    $this->seed();

    $landing = StaticPage::query()->where('slug', 'landing-page')->firstOrFail();
    $blog = StaticPage::query()->where('name', 'blog')->firstOrFail();
    $about = StaticPage::query()->where('slug', 'about-us')->firstOrFail();

    expect($landing->description)->not->toContain('v4')
        ->and($blog->title)->toBe('Laravel Blog')
        ->and($about->description)->toBeString()->not->toBeEmpty();
});

it('seeds an enriched about page with citations', function (): void {
    $this->seed();

    $about = StaticPage::query()->where('slug', 'about-us')->firstOrFail();

    expect($about->content)->toContain('Blog Kit is')
        ->and($about->content)->toContain('laravel.com/docs');
});

it('seeds a demo article only in local environments', function (): void {
    $this->seed();

    expect(Blog::query()->count())->toBe(0);

    $this->app['env'] = 'local';

    $this->seed();

    $post = Blog::query()->first();

    expect($post)->not->toBeNull()
        ->and(mb_strlen($post->description))->toBeGreaterThanOrEqual(120)
        ->and(mb_strlen($post->description))->toBeLessThanOrEqual(160)
        ->and($post->seo?->meta_description)->not->toBeNull();
});

it('seeds faq answers long enough for answer engines', function (): void {
    $this->seed();

    $answers = Faq::query()->pluck('answer');

    expect($answers)->toHaveCount(6);

    foreach ($answers as $answer) {
        $words = str_word_count(strip_tags((string) $answer));

        expect($words)->toBeGreaterThanOrEqual(40)
            ->and($words)->toBeLessThanOrEqual(60);
    }
});
