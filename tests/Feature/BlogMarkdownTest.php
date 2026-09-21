<?php

declare(strict_types=1);

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;

beforeEach(function (): void {
    $this->author = User::factory()->create(['name' => 'Jane Writer']);

    $this->category = Category::query()->create(['name' => 'Laravel']);

    $this->blog = Blog::query()->create([
        'title' => 'Markdown Article',
        'description' => 'A description for agents.',
        'content' => '<p>Intro paragraph.</p><h2>What is included</h2><ul><li>Filament admin</li><li>Livewire pages</li></ul>',
        'tags' => ['laravel', 'markdown'],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDay(),
    ]);

    $this->blog->categories()->attach($this->category);
});

it('serves an article as markdown with frontmatter', function (): void {
    $response = $this->get(route('blog.markdown', $this->blog));

    $response->assertOk();

    $content = (string) $response->getContent();

    expect($response->headers->get('Content-Type'))->toContain('text/markdown')
        ->and($content)->toStartWith("---\n")
        ->and($content)->toContain('title: "Markdown Article"')
        ->and($content)->toContain('type: "article"')
        ->and($content)->toContain('url: "'.route('blog.view', $this->blog).'"')
        ->and($content)->toContain('markdown_url: "'.route('blog.markdown', $this->blog).'"')
        ->and($content)->toContain('reading_time:')
        ->and($content)->toContain('seo:')
        ->and($content)->toContain('# Markdown Article')
        ->and($content)->toContain('## What is included')
        ->and($content)->toContain('- Filament admin');
});

it('returns 404 for a draft article markdown', function (): void {
    $draft = Blog::query()->create([
        'title' => 'Draft Article',
        'description' => 'Not published.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => null,
    ]);

    $this->get(route('blog.markdown', $draft))->assertNotFound();
});

it('returns 404 for a noindex article markdown', function (): void {
    $this->blog->seo()->update(['robots' => ['noindex', 'follow']]);

    $this->get(route('blog.markdown', $this->blog))->assertNotFound();
});

it('prefers the seo metadata in the frontmatter', function (): void {
    $this->blog->seo()->update([
        'meta_title' => 'Frontmatter SEO Title',
        'meta_description' => 'Frontmatter SEO description.',
    ]);

    $content = (string) $this->get(route('blog.markdown', $this->blog))->getContent();

    expect($content)->toContain('title: "Frontmatter SEO Title"')
        ->and($content)->toContain('description: "Frontmatter SEO description."');
});
