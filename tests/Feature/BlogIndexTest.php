<?php

declare(strict_types=1);

use App\Enums\PageType;
use App\Models\Blog;
use App\Models\StaticPage;
use App\Models\User;

beforeEach(function (): void {
    StaticPage::query()->create([
        'name' => 'blog',
        'type' => PageType::IndexPage,
        'title' => 'Laravel Blog',
        'description' => 'Guides and tutorials for Laravel bloggers.',
        'tags' => [],
    ]);

    $author = User::factory()->create();

    Blog::query()->create([
        'title' => 'First Article',
        'description' => 'An article description.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $author->getKey(),
        'published_at' => now()->subDay(),
    ]);
});

it('renders exactly one H1 on the blog index', function (): void {
    $content = (string) $this->get(route('blog.index'))->getContent();

    expect(preg_match_all('/<h1[\s>]/i', $content))->toBe(1)
        ->and($content)->toContain('Laravel Blog');
});
