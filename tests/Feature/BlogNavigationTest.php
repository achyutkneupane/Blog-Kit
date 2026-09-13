<?php

declare(strict_types=1);

use App\Enums\PageType;
use App\Models\Blog;
use App\Models\Category;
use App\Models\StaticPage;
use App\Models\User;

beforeEach(function (): void {
    StaticPage::query()->create([
        'name' => 'blog',
        'type' => PageType::IndexPage,
        'title' => 'Laravel Blog',
        'description' => 'Guides and tutorials.',
        'tags' => [],
    ]);

    $this->author = User::factory()->create();
    $this->category = Category::query()->create(['name' => 'Laravel']);

    $this->blog = Blog::query()->create([
        'title' => 'Filterable Article',
        'description' => 'An article description.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDay(),
    ]);

    $this->blog->categories()->attach($this->category);
});

it('links category chips to the filtered blog index', function (): void {
    $content = (string) $this->get(route('blog.view', $this->blog))->getContent();

    expect($content)->toContain('href="'.route('blog.index', ['selectedCategories' => [$this->category->id]]).'"');
});

it('filters the blog index by category from the url', function (): void {
    $otherCategory = Category::query()->create(['name' => 'PHP']);
    $other = Blog::query()->create([
        'title' => 'Other Category Article',
        'description' => 'Another article.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDay(),
    ]);
    $other->categories()->attach($otherCategory);

    $content = (string) $this->get(route('blog.index', ['selectedCategories' => [$this->category->id]]))->getContent();

    expect($content)->toContain('Filterable Article')
        ->and($content)->not->toContain('Other Category Article');
});

it('paginates the blog index', function (): void {
    foreach (range(1, 10) as $index) {
        Blog::query()->create([
            'title' => 'Paged Article '.$index,
            'description' => 'Paged description '.$index,
            'content' => '<p>Body</p>',
            'tags' => [],
            'user_id' => $this->author->getKey(),
            'published_at' => now()->subDays($index + 1),
        ]);
    }

    $firstPage = (string) $this->get(route('blog.index'))->getContent();
    $secondPage = (string) $this->get(route('blog.index', ['page' => 2]))->getContent();

    expect(mb_substr_count($firstPage, '<article'))->toBe(9)
        ->and(mb_substr_count($secondPage, '<article'))->toBe(2);
});

it('shows related articles from shared categories', function (): void {
    $related = Blog::query()->create([
        'title' => 'Related Laravel Article',
        'description' => 'Another Laravel article.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDay(),
    ]);
    $related->categories()->attach($this->category);

    $unrelated = Blog::query()->create([
        'title' => 'Unrelated Node Article',
        'description' => 'An unrelated article.',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $this->author->getKey(),
        'published_at' => now()->subDay(),
    ]);

    $content = (string) $this->get(route('blog.view', $this->blog))->getContent();

    expect($content)->toContain('Related Articles')
        ->and($content)->toContain('Related Laravel Article')
        ->and($content)->not->toContain($unrelated->title);
});

it('renders breadcrumbs on article, page, author and faq templates', function (): void {
    $about = StaticPage::query()->create([
        'title' => 'About Us',
        'description' => 'About page.',
        'slug' => 'about-us',
        'name' => null,
        'type' => PageType::ContentPage,
        'tags' => [],
        'content' => '<p>About</p>',
    ]);

    StaticPage::query()->create([
        'title' => 'FAQ',
        'description' => 'FAQ page.',
        'slug' => 'faq',
        'name' => 'faq',
        'type' => PageType::Faq,
        'tags' => [],
        'content' => '<p>FAQ</p>',
    ]);

    foreach ([
        route('blog.index'),
        route('blog.view', $this->blog),
        route('page.view', $about),
        route('author.view', $this->author),
        route('faq.view'),
    ] as $url) {
        $content = (string) $this->get($url)->getContent();

        expect($content)->toContain('aria-label="Breadcrumb"')
            ->and($content)->toContain('aria-current="page"');
    }
});
