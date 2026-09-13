<?php

declare(strict_types=1);

use App\Enums\PageType;
use App\Enums\UserRole;
use App\Models\Faq;
use App\Models\StaticPage;
use App\Models\User;

beforeEach(function (): void {
    StaticPage::query()->create([
        'title' => 'Frequently Asked Questions',
        'description' => 'Answers to common questions about Blog Kit.',
        'slug' => 'faq',
        'name' => 'faq',
        'type' => PageType::Faq,
        'tags' => ['faq'],
        'content' => '<p>Intro</p>',
    ]);

    StaticPage::query()->create([
        'title' => 'Laravel Blog Starter Kit',
        'description' => 'Landing page.',
        'slug' => 'landing-page',
        'name' => null,
        'type' => PageType::LandingPage,
        'tags' => [],
    ]);

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

it('renders the faq page with active questions only', function (): void {
    $response = $this->get(route('faq.view'));

    $response->assertOk();

    $content = (string) $response->getContent();

    expect($content)->toContain('What is Blog Kit?')
        ->and($content)->toContain('Blog Kit is a Laravel starter kit for blogging.')
        ->and($content)->not->toContain('Is there a license?');
});

it('emits faq page schema on the faq page', function (): void {
    $content = (string) $this->get(route('faq.view'))->getContent();

    expect($content)->toContain('"@type":"FAQPage"')
        ->and($content)->toContain('"@type":"Question"')
        ->and($content)->toContain('"@type":"Answer"')
        ->and($content)->toContain('"name":"What is Blog Kit?"')
        ->and($content)->toContain('rel="canonical" href="'.route('faq.view').'"');
});

it('includes the faq page in the sitemap', function (): void {
    $content = (string) $this->get('/sitemap.xml')->getContent();

    expect($content)->toContain(route('faq.view'));
});

it('shows faqs with schema on the homepage', function (): void {
    $content = (string) $this->get(route('landing-page'))->getContent();

    expect($content)->toContain('What is Blog Kit?')
        ->and($content)->toContain('"@type":"FAQPage"')
        ->and($content)->toContain('href="'.route('faq.view').'"');
});

it('manages faqs in the admin panel', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Developer]);

    $this->actingAs($admin)->get('/admin/faqs')->assertOk();
});
