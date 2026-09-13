<?php

declare(strict_types=1);

use App\Enums\PageType;
use App\Models\StaticPage;
use App\Settings\SiteSettings;
use App\Settings\SocialMediaSettings;

beforeEach(function (): void {
    StaticPage::query()->create([
        'title' => 'Laravel Blog Starter Kit',
        'description' => 'Landing page.',
        'slug' => 'landing-page',
        'name' => null,
        'type' => PageType::LandingPage,
        'tags' => [],
    ]);

    StaticPage::query()->create([
        'title' => 'Contact',
        'description' => 'Questions, feedback or partnership ideas welcome.',
        'slug' => 'contact',
        'name' => 'contact',
        'type' => PageType::Contact,
        'tags' => [],
        'content' => '<p>Get in touch.</p>',
    ]);

    $settings = app(SiteSettings::class);
    $settings->contact_email = 'hello@blogkit.test';
    $settings->save();
});

it('renders the contact page with email and contact schema', function (): void {
    $content = (string) $this->get(route('contact.view'))->getContent();

    expect($content)->toContain('hello@blogkit.test')
        ->and($content)->toContain('"@type":"ContactPage"')
        ->and($content)->toContain('"@type":"ContactPoint"')
        ->and($content)->toContain('rel="canonical" href="'.route('contact.view').'"');
});

it('shows the contact link in header and footer navigation', function (): void {
    $content = (string) $this->get(route('landing-page'))->getContent();

    expect(mb_substr_count($content, 'href="'.route('contact.view').'"'))->toBeGreaterThanOrEqual(2);
});

it('includes the contact page in the sitemap', function (): void {
    $content = (string) $this->get('/sitemap.xml')->getContent();

    expect($content)->toContain(route('contact.view'));
});

it('renders configured social profiles in the footer', function (): void {
    $social = app(SocialMediaSettings::class);
    $social->linkedin = 'https://linkedin.com/company/blogkit';
    $social->github = 'https://github.com/achyutkneupane/Blog-Kit';
    $social->save();

    $content = (string) $this->get(route('landing-page'))->getContent();

    expect($content)->toContain('href="https://linkedin.com/company/blogkit"')
        ->and($content)->toContain('href="https://github.com/achyutkneupane/Blog-Kit"')
        ->and($content)->toContain('rel="me noopener"');
});
