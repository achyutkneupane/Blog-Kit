<?php

declare(strict_types=1);

use App\Models\Blog;
use App\Models\User;

beforeEach(function (): void {
    $this->emptyAuthor = User::factory()->create(['name' => 'Empty Author']);

    $this->author = User::factory()->create(['name' => 'Published Author']);

    Blog::query()->create([
        'title' => 'Published Post',
        'description' => 'A published post description.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDay(),
    ]);
});

it('noindexes author pages without published posts', function (): void {
    $content = (string) $this->get(route('author.view', $this->emptyAuthor))->getContent();

    expect($content)->toContain('content="noindex, nofollow"')
        ->and(mb_substr_count($content, 'name="robots"'))->toBe(1);
});

it('keeps author pages indexable when they have published posts', function (): void {
    $content = (string) $this->get(route('author.view', $this->author))->getContent();

    expect($content)->not->toContain('content="noindex, nofollow"');
});

it('excludes empty author pages from both sitemaps', function (): void {
    $emptyUrl = route('author.view', $this->emptyAuthor);
    $publishedUrl = route('author.view', $this->author);

    $xml = (string) $this->get('/sitemap.xml')->getContent();
    $txt = (string) $this->get('/sitemap.txt')->getContent();

    expect($xml)->toContain($publishedUrl)->not->toContain($emptyUrl)
        ->and($txt)->toContain($publishedUrl)->not->toContain($emptyUrl);
});
