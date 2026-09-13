<?php

declare(strict_types=1);

use App\Models\Blog;
use App\Models\User;

beforeEach(function (): void {
    $this->author = User::factory()->create();

    $this->blog = Blog::query()->create([
        'title' => 'Schema Article',
        'description' => 'Article description for schema tests.',
        'content' => '<p>Body</p>',
        'tags' => ['laravel', 'seo'],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDay(),
    ]);
});

it('emits a single complete BlogPosting schema on posts', function (): void {
    $content = (string) $this->get(route('blog.view', $this->blog))->getContent();

    preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);

    $blocks = array_map(fn (string $json): mixed => json_decode($json, true), $matches[1]);

    $blogPostings = array_values(array_filter(
        $blocks,
        fn (mixed $block): bool => is_array($block) && ($block['@type'] ?? null) === 'BlogPosting',
    ));

    $articles = array_filter(
        $blocks,
        fn (mixed $block): bool => is_array($block) && ($block['@type'] ?? null) === 'Article',
    );

    expect($blogPostings)->toHaveCount(1)
        ->and($articles)->toBeEmpty();

    $schema = $blogPostings[0];

    expect($schema)->toHaveKeys([
        'headline',
        'description',
        'url',
        'image',
        'datePublished',
        'dateModified',
        'author',
        'publisher',
        'mainEntityOfPage',
    ])
        ->and($schema['headline'])->toBe('Schema Article')
        ->and($schema['author']['@type'])->toBe('Person')
        ->and($schema['author']['url'])->toBe(route('author.view', $this->author))
        ->and($schema['publisher']['@type'])->toBe('Organization')
        ->and($schema['keywords'])->toBe('laravel, seo');
});
