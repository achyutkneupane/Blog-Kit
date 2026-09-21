<?php

declare(strict_types=1);

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;

beforeEach(function (): void {
    $this->author = User::factory()->create();

    $this->category = Category::query()->create(['name' => 'Laravel']);

    $this->blog = Blog::query()->create([
        'title' => 'Categorised Article',
        'description' => 'An article description for agents.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDay(),
    ]);

    $this->blog->categories()->attach($this->category);
});

it('serves a category manifest as markdown', function (): void {
    $response = $this->get(route('category.markdown', $this->category));

    $response->assertOk();

    $content = (string) $response->getContent();

    expect($response->headers->get('Content-Type'))->toContain('text/markdown')
        ->and($content)->toStartWith("---\n")
        ->and($content)->toContain('title: "Laravel"')
        ->and($content)->toContain('type: "category"')
        ->and($content)->toContain('article_count: 1')
        ->and($content)->toContain('url: "'.route('category.view', $this->category).'"')
        ->and($content)->toContain('markdown_url: "'.route('category.markdown', $this->category).'"')
        ->and($content)->toContain('Categorised Article')
        ->and($content)->toContain('An article description for agents.')
        ->and($content)->toContain(route('blog.view', $this->blog))
        ->and($content)->toContain(route('blog.markdown', $this->blog));
});

it('excludes drafts from the category manifest', function (): void {
    $draft = Blog::query()->create([
        'title' => 'Draft Categorised Article',
        'description' => 'Not published.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => null,
    ]);

    $draft->categories()->attach($this->category);

    $content = (string) $this->get(route('category.markdown', $this->category))->getContent();

    expect($content)->not->toContain('Draft Categorised Article');
});

it('excludes noindex articles from the category manifest', function (): void {
    $this->blog->seo()->update(['robots' => ['noindex', 'follow']]);

    $content = (string) $this->get(route('category.markdown', $this->category))->getContent();

    expect($content)->not->toContain('Categorised Article');
});
