<?php

declare(strict_types=1);

use App\Enums\PageType;
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
