<?php

declare(strict_types=1);

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;

beforeEach(function (): void {
    $author = User::factory()->create();

    $this->category = Category::query()->create(['name' => 'Laravel']);

    $this->blog = Blog::query()->create([
        'title' => 'Categorised Article',
        'description' => 'An article description.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $author->getKey(),
        'published_at' => now()->subDay(),
    ]);

    $this->blog->categories()->attach($this->category);
});

it('renders the category page with its articles', function (): void {
    $response = $this->get(route('category.view', $this->category));

    $response->assertOk();

    $content = (string) $response->getContent();

    expect($content)->toContain('Laravel')
        ->and($content)->toContain('Categorised Article');
});

it('returns 404 for an unknown category', function (): void {
    $this->get('/category/does-not-exist')->assertNotFound();
});

it('exposes a human readable url value for the category', function (): void {
    expect($this->category->getUrlValue())->toBe(route('category.view', $this->category));
});
