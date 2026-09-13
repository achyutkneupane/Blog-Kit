<?php

declare(strict_types=1);

use App\Enums\PageType;
use App\Models\Blog;
use App\Models\StaticPage;
use App\Models\User;
use App\Settings\SocialMediaSettings;

beforeEach(function (): void {
    config()->set('seo.site_name', 'Blog Kit');
    config()->set('seo.twitter.@username', 'blogkit');

    $settings = app(SocialMediaSettings::class);
    $settings->x = '';
    $settings->save();

    $author = User::factory()->create();

    $this->blog = Blog::query()->create([
        'title' => 'SEO Metadata',
        'description' => 'A description for metadata testing.',
        'content' => '<p>Body</p>',
        'tags' => ['seo'],
        'user_id' => $author->getKey(),
        'published_at' => now()->subDay(),
    ]);

    $this->page = StaticPage::query()->create([
        'title' => 'Home',
        'description' => 'Landing page description.',
        'slug' => 'landing-page',
        'name' => null,
        'type' => PageType::LandingPage,
        'tags' => [],
    ]);
});

it('appends the configured title suffix to the title and og title', function (): void {
    $content = (string) $this->get(route('blog.view', $this->blog))->getContent();

    expect($content)->toContain('<title>SEO Metadata - Blog Kit</title>')
        ->and($content)->toContain('property="og:title" content="SEO Metadata - Blog Kit"');
});

it('emits a single robots tag with vendor directives', function (): void {
    $content = (string) $this->get(route('blog.view', $this->blog))->getContent();

    expect(mb_substr_count($content, 'name="robots"'))->toBe(1)
        ->and($content)->toContain('index, follow, max-snippet:-1,max-image-preview:large,max-video-preview:-1');
});

it('marks pages as noindex outside of production', function (): void {
    $this->app['env'] = 'local';

    $content = (string) $this->get(route('blog.view', $this->blog))->getContent();

    expect(mb_substr_count($content, 'name="robots"'))->toBe(1)
        ->and($content)->toContain('content="noindex, nofollow"');
});

it('outputs website type for static pages and article type for posts', function (): void {
    expect($this->page->getDynamicSEOData()->type)->toBe('website')
        ->and($this->blog->getDynamicSEOData()->type)->toBe('article');

    $content = (string) $this->get(route('blog.view', $this->blog))->getContent();

    expect($content)->toContain('property="og:type" content="article"');
});

it('emits the site name as og site name', function (): void {
    $content = (string) $this->get(route('blog.view', $this->blog))->getContent();

    expect($content)->toContain('property="og:site_name" content="Blog Kit"');
});

it('emits twitter site and creator tags', function (): void {
    $content = (string) $this->get(route('blog.view', $this->blog))->getContent();

    expect($content)->toContain('name="twitter:site" content="@blogkit"')
        ->and($content)->toContain('name="twitter:creator" content="@blogkit"');
});

it('derives the twitter handle from social settings when config is empty', function (): void {
    config()->set('seo.twitter.@username', null);

    $settings = app(SocialMediaSettings::class);
    $settings->x = '@blogkitx';
    $settings->save();

    $content = (string) $this->get(route('blog.view', $this->blog))->getContent();

    expect($content)->toContain('name="twitter:site" content="@blogkitx"');
});

it('respects stored robots and og title overrides', function (): void {
    $this->blog->seo()->update([
        'robots' => ['noindex'],
        'og_title' => 'Custom OG Title',
    ]);

    $data = $this->blog->refresh()->getDynamicSEOData();

    expect($data->robots)->toBe('noindex')
        ->and($data->openGraphTitle)->toBe('Custom OG Title');
});
