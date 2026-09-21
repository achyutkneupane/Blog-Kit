<?php

declare(strict_types=1);

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;

beforeEach(function (): void {
    $author = User::factory()->create();

    $this->category = Category::query()->create(['name' => 'Laravel']);

    $this->blog = Blog::query()->create([
        'title' => 'Routable Article',
        'description' => 'An article description.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $author->getKey(),
        'published_at' => now()->subDay(),
    ]);

    $this->blog->categories()->attach($this->category);
});

it('keeps the human article page and the markdown variant separate', function (): void {
    $page = $this->get(route('blog.view', $this->blog));

    $page->assertOk();

    expect($page->headers->get('Content-Type'))->toContain('text/html')
        ->and((string) $page->getContent())->not->toStartWith('---');

    $markdown = $this->get(route('blog.markdown', $this->blog));

    $markdown->assertOk();

    expect($markdown->headers->get('Content-Type'))->toContain('text/markdown')
        ->and((string) $markdown->getContent())->toStartWith("---\n");
});

it('keeps the human category page and the markdown variant separate', function (): void {
    $page = $this->get(route('category.view', $this->category));

    $page->assertOk();

    expect($page->headers->get('Content-Type'))->toContain('text/html')
        ->and((string) $page->getContent())->not->toStartWith('---');

    $markdown = $this->get(route('category.markdown', $this->category));

    $markdown->assertOk();

    expect($markdown->headers->get('Content-Type'))->toContain('text/markdown')
        ->and((string) $markdown->getContent())->toStartWith("---\n");
});
