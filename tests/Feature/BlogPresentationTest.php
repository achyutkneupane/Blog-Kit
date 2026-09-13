<?php

declare(strict_types=1);

use App\Enums\PageType;
use App\Models\Blog;
use App\Models\StaticPage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');

    $author = User::factory()->create();

    StaticPage::query()->create([
        'name' => 'blog',
        'type' => PageType::IndexPage,
        'title' => 'Blog',
        'description' => 'Blog index',
        'tags' => [],
    ]);

    $this->withCover = Blog::query()->create([
        'title' => 'With Cover',
        'description' => 'Has a cover image.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $author->getKey(),
        'published_at' => now()->subDay(),
    ]);

    $this->withCover
        ->addMedia(UploadedFile::fake()->image('cover.jpg', 800, 420))
        ->toMediaCollection('cover');

    $this->withoutCover = Blog::query()->create([
        'title' => 'Without Cover',
        'description' => 'Has no cover image.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $author->getKey(),
        'published_at' => now()->subDay(),
    ]);
});

it('lazy loads blog card covers with intrinsic dimensions', function (): void {
    $content = (string) $this->get(route('blog.index'))->getContent();

    expect($content)->toContain('loading="lazy"')
        ->and($content)->toContain('alt="With Cover"')
        ->and($content)->toContain('width="300"')
        ->and($content)->toContain('height="160"');
});

it('renders a cover placeholder when media is missing', function (): void {
    $content = (string) $this->get(route('blog.index'))->getContent();

    expect($content)->toContain('cover-placeholder');
});

it('renders the article cover with high priority', function (): void {
    $content = (string) $this->get(route('blog.view', $this->withCover))->getContent();

    expect($content)->toContain('fetchpriority="high"')
        ->and($content)->toContain('alt="With Cover"')
        ->and($content)->toContain('width="800"')
        ->and($content)->toContain('height="420"');
});

it('sizes the author avatar', function (): void {
    $content = (string) $this->get(route('blog.view', $this->withCover))->getContent();

    expect($content)->toContain('width="56"')
        ->and($content)->toContain('height="56"');
});

it('loads the hero image with priority and intrinsic dimensions', function (): void {
    $this->blade('<x-section.hero-section />')
        ->assertSee('fetchpriority="high"', false)
        ->assertSee('width="3328"', false)
        ->assertSee('height="2006"', false);
});
