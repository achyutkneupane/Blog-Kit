<?php

declare(strict_types=1);

use App\Models\Blog;
use App\Models\User;

beforeEach(function (): void {
    $this->author = User::factory()->create([
        'name' => 'Jane Author',
        'job_title' => 'Senior Writer',
        'bio' => 'Jane writes about Laravel and the TALL stack.',
        'website' => 'https://jane.example.com',
    ]);

    $this->blog = Blog::query()->create([
        'title' => 'An Article By Jane',
        'description' => 'Article description.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDay(),
    ]);
});

it('generates a slug for every author', function (): void {
    expect($this->author->slug)->toBe('jane-author');
});

it('renders an author page with their published blogs', function (): void {
    $response = $this->get(route('author.view', $this->author));

    $response->assertOk();

    $content = (string) $response->getContent();

    expect($content)->toContain('Jane Author')
        ->and($content)->toContain('Senior Writer')
        ->and($content)->toContain('An Article By Jane')
        ->and($content)->toContain('https://jane.example.com');
});

it('emits profile page and person schema on the author page', function (): void {
    $content = (string) $this->get(route('author.view', $this->author))->getContent();

    expect($content)->toContain('"@type":"ProfilePage"')
        ->and($content)->toContain('"@type":"Person"')
        ->and($content)->toContain('"jobTitle":"Senior Writer"')
        ->and($content)->toContain(route('author.view', $this->author));
});

it('links blog authors to their author page', function (): void {
    $content = (string) $this->get(route('blog.view', $this->blog))->getContent();
    $authorUrl = route('author.view', $this->author);
    $escapedAuthorUrl = str_replace('/', '\/', $authorUrl);

    expect($content)->toContain('href="'.$authorUrl.'"')
        ->and($content)->toContain('"url":"'.$escapedAuthorUrl.'"');
});

it('includes author pages in the sitemap', function (): void {
    $content = (string) $this->get('/sitemap.xml')->getContent();

    expect($content)->toContain(route('author.view', $this->author));
});

it('generates an og image for the author', function (): void {
    expect($this->author->ogImageUrl())
        ->toBeString()
        ->toContain('/open-graph/author/'.$this->author->slug);
});

it('returns 404 for an unknown author', function (): void {
    $this->get('/author/unknown-author')->assertNotFound();
});
