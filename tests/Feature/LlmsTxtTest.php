<?php

declare(strict_types=1);

use App\Enums\PageType;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Faq;
use App\Models\StaticPage;
use App\Models\User;
use App\Settings\SiteSettings;

beforeEach(function (): void {
    StaticPage::query()->create([
        'title' => 'About Us',
        'description' => 'Who we are.',
        'slug' => 'about-us',
        'name' => null,
        'type' => PageType::ContentPage,
        'tags' => [],
    ]);

    $this->author = User::factory()->create(['name' => 'Jane Writer']);

    $this->category = Category::query()->create(['name' => 'Laravel']);

    $this->blog = Blog::query()->create([
        'title' => 'Published Article',
        'description' => 'A published article description.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDay(),
    ]);

    $this->blog->categories()->attach($this->category);

    Faq::query()->create([
        'question' => 'What is Blog Kit?',
        'answer' => 'Blog Kit is a Laravel starter kit for blogging.',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Faq::query()->create([
        'question' => 'Is there a license?',
        'answer' => 'Yes, Blog Kit is MIT licensed.',
        'is_active' => false,
        'sort_order' => 2,
    ]);
});

it('serves an llms file with pages, categories, authors, faqs and links', function (): void {
    $settings = app(SiteSettings::class);

    $response = $this->get('/llms.txt');

    $response->assertOk();

    $content = (string) $response->getContent();

    expect($response->headers->get('Content-Type'))->toContain('text/plain')
        ->and($content)->toContain('# '.$settings->name)
        ->and($content)->toContain('## Pages')
        ->and($content)->toContain('About Us')
        ->and($content)->toContain('## Categories')
        ->and($content)->toContain(route('category.view', $this->category))
        ->and($content)->toContain(route('category.markdown', $this->category))
        ->and($content)->toContain('## Authors')
        ->and($content)->toContain('## FAQs')
        ->and($content)->toContain('What is Blog Kit?')
        ->and($content)->not->toContain('Is there a license?')
        ->and($content)->toContain('## Articles')
        ->and($content)->toContain(route('llms.blogs'))
        ->and($content)->toContain('## Optional')
        ->and($content)->toContain(url('/sitemap.xml'))
        ->and($content)->toContain(url('/ai.txt'));
});

it('does not list individual articles in the llms file', function (): void {
    $content = (string) $this->get('/llms.txt')->getContent();

    expect($content)->not->toContain('Published Article')
        ->and($content)->not->toContain(route('blog.view', $this->blog));
});

it('refreshes the llms file when content changes', function (): void {
    $this->get('/llms.txt');

    $this->category->update(['name' => 'Laravel Framework']);

    expect((string) $this->get('/llms.txt')->getContent())->toContain('Laravel Framework');
});
