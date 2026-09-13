<?php

declare(strict_types=1);

use App\Enums\PageType;
use App\Models\Blog;
use App\Models\StaticPage;
use App\Models\User;

beforeEach(function (): void {
    StaticPage::query()->create([
        'title' => 'About Us',
        'description' => 'Who we are.',
        'slug' => 'about-us',
        'name' => null,
        'type' => PageType::ContentPage,
        'tags' => [],
    ]);

    $this->author = User::factory()->create();

    $this->blog = Blog::query()->create([
        'title' => 'Published Article',
        'description' => 'A published article description.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDay(),
    ]);
});

it('serves an llms file listing pages and published articles', function (): void {
    $settings = app(App\Settings\SiteSettings::class);

    $response = $this->get('/llms.txt');

    $response->assertOk();

    $content = (string) $response->getContent();

    expect($response->headers->get('Content-Type'))->toContain('text/plain')
        ->and($content)->toContain('# '.$settings->name)
        ->and($content)->toContain('Published Article')
        ->and($content)->toContain(route('blog.view', $this->blog))
        ->and($content)->toContain('About Us');
});

it('excludes unpublished articles from the llms file', function (): void {
    Blog::query()->create([
        'title' => 'Draft Article',
        'description' => 'Not published yet.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => null,
    ]);

    $content = (string) $this->get('/llms.txt')->getContent();

    expect($content)->not->toContain('Draft Article');
});

it('refreshes the llms file when a blog is published', function (): void {
    $this->get('/llms.txt');

    $this->blog->update(['title' => 'Renamed Article']);

    expect((string) $this->get('/llms.txt')->getContent())->toContain('Renamed Article');
});

it('serves an ai policy file', function (): void {
    $response = $this->get('/ai.txt');

    $response->assertOk();

    $content = (string) $response->getContent();

    expect($content)->toContain('GPTBot')
        ->and($content)->toContain('ClaudeBot')
        ->and($content)->toContain('PerplexityBot')
        ->and($content)->toContain('Google-Extended')
        ->and($content)->toContain('Disallow: /admin')
        ->and($content)->toContain('Sitemap: '.url('/sitemap.xml'));
});
