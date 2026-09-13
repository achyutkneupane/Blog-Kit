<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PageType;
use App\Enums\UserRole;
use App\Models\Faq;
use App\Models\StaticPage;
use App\Models\User;
use App\Settings\SiteSettings;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(
        SiteSettings $siteSettings
    ): void {
        if (app()->isLocal()) {
            $developer = User::query()->firstOrCreate([
                'email' => 'developer@test.com',
            ], [
                'name' => 'Blog Developer',
                'role' => UserRole::Developer,
                'password' => bcrypt('password'),
            ]);

            $developer->update([
                'job_title' => 'Founder & Lead Developer',
                'bio' => 'Maintainer of Blog Kit, building fast and SEO-friendly Laravel applications with the TALL stack.',
                'website' => 'https://github.com/achyutkneupane',
            ]);

            $admin = User::query()->firstOrCreate([
                'email' => 'admin@test.com',
            ], [
                'name' => 'Blog Admin',
                'role' => UserRole::Admin,
                'password' => bcrypt('password'),
            ]);

            $admin->update([
                'job_title' => 'Managing Editor',
                'bio' => 'Keeps the publication running: reviews drafts, schedules posts, and maintains the editorial calendar.',
                'website' => 'https://laravelnepal.com',
            ]);

            $writer = User::query()->firstOrCreate([
                'email' => 'writer@test.com',
            ], [
                'name' => 'Blog Writer',
                'role' => UserRole::Writer,
                'password' => bcrypt('password'),
            ]);

            $writer->update([
                'job_title' => 'Staff Writer',
                'bio' => 'Writes practical Laravel tutorials and TALL stack deep dives for the Blog Kit publication.',
                'website' => 'https://laravel.com',
            ]);

            $reader = User::query()->firstOrCreate([
                'email' => 'user@test.com',
            ], [
                'name' => 'Blog User',
                'role' => UserRole::User,
                'password' => bcrypt('password'),
            ]);

            $reader->update([
                'job_title' => 'Community Member',
                'bio' => 'Reads, comments, and shares the articles published with Blog Kit.',
                'website' => 'https://laravel-news.com',
            ]);
        }

        $applySeo = function (
            StaticPage $page,
            string $title,
            string $description,
        ): void {
            $page->seo()->updateOrCreate([
                'model_id' => $page->getKey(),
                'model_type' => $page->getMorphClass(),
            ], [
                'meta_title' => $title,
                'meta_description' => $description,
                'og_title' => $title,
                'og_description' => $description,
                'robots' => ['index', 'follow'],
            ]);
        };

        $landingPage = StaticPage::query()->firstOrCreate([
            'slug' => 'landing-page',
            'type' => PageType::LandingPage,
        ], [
            'title' => 'Laravel Blog Starter Kit',
            'description' => $siteSettings->description,
            'tags' => ['blogs', 'kit', 'laravel'],
        ]);

        $applySeo(
            $landingPage,
            'Laravel Blog Starter Kit',
            'A production-ready Laravel blog starter kit with Filament admin, Livewire pages, SEO tooling, and dynamic social cards. Launch your blog in minutes.',
        );

        $blogIndex = StaticPage::query()->firstOrCreate([
            'name' => 'blog',
            'type' => PageType::IndexPage,
        ], [
            'title' => 'Blog',
            'description' => sprintf('Explore all blogs published in %s', $siteSettings->name),
            'tags' => ['blogs', 'articles', 'posts'],
        ]);

        $applySeo(
            $blogIndex,
            'Laravel Blog: Guides, Tutorials & Updates',
            'Read Laravel guides, TALL stack tutorials, and product updates from the Blog Kit team. Practical articles on building fast, SEO-friendly blogs.',
        );

        $aboutPage = StaticPage::query()->firstOrCreate([
            'slug' => 'about-us',
            'type' => PageType::ContentPage,
        ], [
            'title' => 'About Us',
            'description' => sprintf('Learn more about %s and our mission to provide quality content to the Laravel community.', $siteSettings->name),
            'tags' => ['about', 'blog', 'laravel'],
            'content' => implode('', [
                '<p>Welcome to the Blog Kit! This is a simple starter kit for building a blog using Laravel and Tailwind CSS.</p>',
                '<p>The pages are SEO optimized and responsive. The process is simple: create a new laravel project using the starter kit, set up your database, change the page designs as you like, and start writing blog posts!</p>',
                '<p>Feel free to contribute to the project on <a href="https://github.com/achyutkneupane/Blog-Kit">GitHub</a> or reach out to me on <a href="https://www.linkedin.com/in/achyutneupane">LinkedIn</a>.</p>',
                '<p>Happy blogging!</p>',
            ]),
        ]);

        $applySeo(
            $aboutPage,
            'About Blog Kit: Our Mission',
            'Learn who is behind Blog Kit and why we built an open source Laravel starter kit for fast, SEO-friendly blogging with the TALL stack.',
        );

        $faqPage = StaticPage::query()->firstOrCreate([
            'slug' => 'faq',
            'type' => PageType::Faq,
        ], [
            'title' => 'Frequently Asked Questions',
            'name' => 'faq',
            'description' => 'Answers to the most common questions about Blog Kit, from setup to publishing.',
            'tags' => ['faq', 'help', 'support'],
            'content' => '<p>Everything you need to know about Blog Kit, from setup to publishing.</p>',
        ]);

        $applySeo(
            $faqPage,
            'Frequently Asked Questions',
            'Answers to the most common questions about Blog Kit: setup, publishing, SEO tooling, and customization options.',
        );

        $faqs = [
            [
                'question' => 'What is Blog Kit?',
                'answer' => 'Blog Kit is an open source Laravel starter kit for building SEO-friendly blogs with Filament, Livewire, and Tailwind CSS.',
                'sort_order' => 1,
            ],
            [
                'question' => 'Do I need Node and Puppeteer?',
                'answer' => 'Only for dynamic social cards. Open Graph image generation uses Browsershot and can be disabled with the OG_IMAGE_ENABLED environment variable.',
                'sort_order' => 2,
            ],
            [
                'question' => 'Which PHP and Laravel versions are supported?',
                'answer' => 'Blog Kit targets PHP 8.3 or newer and Laravel 13, together with Livewire 4 and Filament 5.',
                'sort_order' => 3,
            ],
            [
                'question' => 'Can I customize the design?',
                'answer' => 'Yes. Every public page is built with Tailwind CSS utilities and Livewire single file components, so you can restyle it without touching PHP.',
                'sort_order' => 4,
            ],
            [
                'question' => 'How do I add an author profile?',
                'answer' => 'Give the user a bio, job title, and website in the admin panel. A slugged author page with Person schema is generated automatically.',
                'sort_order' => 5,
            ],
            [
                'question' => 'Is Blog Kit multilingual?',
                'answer' => 'The content model and SEO tooling are locale ready, but only English routes ship by default. Add locale prefixes and hreflang when you need them.',
                'sort_order' => 6,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::query()->updateOrCreate([
                'question' => $faq['question'],
            ], [
                'answer' => $faq['answer'],
                'sort_order' => $faq['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}
