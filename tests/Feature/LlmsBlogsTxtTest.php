<?php

declare(strict_types=1);

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;

beforeEach(function (): void {
    $this->author = User::factory()->create();

    $this->category = Category::query()->create(['name' => 'Laravel']);

    $this->featured = Blog::query()->create([
        'title' => 'Featured Article',
        'description' => 'A featured article description.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDays(2),
        'is_featured' => true,
    ]);

    $this->featured->categories()->attach($this->category);

    $this->regular = Blog::query()->create([
        'title' => 'Regular Article',
        'description' => 'A regular article description.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDay(),
    ]);

    $this->regular->categories()->attach($this->category);
});

it('serves a blogs index linking both the human and markdown article', function (): void {
    $response = $this->get(route('llms.blogs'));

    $response->assertOk();

    $content = (string) $response->getContent();

    expect($response->headers->get('Content-Type'))->toContain('text/plain')
        ->and($content)->toContain('# ')
        ->and($content)->toContain('Featured Article')
        ->and($content)->toContain('Regular Article')
        ->and($content)->toContain(route('blog.view', $this->regular))
        ->and($content)->toContain(route('blog.markdown', $this->regular));
});

it('lists the human and markdown links for an article in a single entry', function (): void {
    $content = (string) $this->get(route('llms.blogs'))->getContent();

    $line = collect(explode("\n", $content))
        ->first(fn (string $line): bool => str_contains($line, route('blog.view', $this->regular)));

    expect($line)->not->toBeNull()
        ->and($line)->toStartWith('- [Regular Article](')
        ->and($line)->toContain('([Markdown]('.route('blog.markdown', $this->regular).'))')
        ->and(mb_substr_count($content, route('blog.markdown', $this->regular)))->toBe(1);
});

it('separates featured articles from the other articles', function (): void {
    $content = (string) $this->get(route('llms.blogs'))->getContent();

    expect($content)->toContain('## Featured')
        ->and($content)->toContain('## Other articles')
        ->and(mb_strpos($content, '## Featured'))->toBeLessThan(mb_strpos($content, '## Other articles'));
});

it('excludes unpublished articles from the blogs index', function (): void {
    Blog::query()->create([
        'title' => 'Draft Article',
        'description' => 'Not published yet.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => null,
    ]);

    $content = (string) $this->get(route('llms.blogs'))->getContent();

    expect($content)->not->toContain('Draft Article');
});

it('refreshes the blogs index when an article is renamed', function (): void {
    $this->get(route('llms.blogs'));

    $this->regular->update(['title' => 'Renamed Article']);
    $this->regular->seo()->update(['meta_title' => 'Renamed Article']);

    expect((string) $this->get(route('llms.blogs'))->getContent())->toContain('Renamed Article');
});
