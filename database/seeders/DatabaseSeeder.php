<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PageType;
use App\Enums\UserRole;
use App\Models\Blog;
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

            $article = Blog::query()->updateOrCreate([
                'slug' => 'building-an-seo-friendly-blog-with-laravel',
            ], [
                'title' => 'Building an SEO-Friendly Blog with Laravel',
                'description' => 'A practical walkthrough of Blog Kit: content models, metadata, structured data, author profiles, and dynamic Open Graph images.',
                'tags' => ['laravel', 'seo', 'tutorial'],
                'user_id' => $writer->getKey(),
                'published_at' => now()->subDays(3),
                'content' => implode('', [
                    '<p>Blog Kit gives you a production-ready Laravel blog foundation so you can focus on writing instead of rebuilding metadata, sitemaps, and admin tooling.</p>',
                    '<h2>What is included</h2>',
                    '<ul>',
                    '<li>Filament admin with per-record SEO controls</li>',
                    '<li>Livewire pages with server-rendered metadata</li>',
                    '<li>Generated Open Graph images and author profiles</li>',
                    '<li>Sitemap, robots, llms.txt, and ai.txt endpoints</li>',
                    '</ul>',
                    '<h2>Publishing workflow</h2>',
                    '<p>Write your article, set a cover image, assign a category and tags, then publish. Metadata, schema, and social cards update automatically.</p>',
                ]),
            ]);

            $article->seo()->updateOrCreate([
                'model_id' => $article->getKey(),
                'model_type' => $article->getMorphClass(),
            ], [
                'meta_title' => $article->title,
                'meta_description' => $article->description,
                'og_title' => $article->title,
                'og_description' => $article->description,
                'robots' => ['index', 'follow'],
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

        $landingPage = StaticPage::query()->updateOrCreate([
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

        $blogIndex = StaticPage::query()->updateOrCreate([
            'name' => 'blog',
            'type' => PageType::IndexPage,
        ], [
            'title' => 'Laravel Blog',
            'description' => 'Guides, tutorials and updates for building fast, SEO-friendly Laravel blogs.',
            'tags' => ['blogs', 'articles', 'posts'],
        ]);

        $applySeo(
            $blogIndex,
            'Laravel Blog: Guides, Tutorials & Updates',
            'Read Laravel guides, TALL stack tutorials, and product updates from the Blog Kit team. Practical articles on building fast, SEO-friendly blogs.',
        );

        $aboutPage = StaticPage::query()->updateOrCreate([
            'slug' => 'about-us',
            'type' => PageType::ContentPage,
        ], [
            'title' => 'About Us',
            'description' => 'Learn who is behind Blog Kit and why we built an open source Laravel starter kit for fast, SEO-friendly blogging.',
            'tags' => ['about', 'blog', 'laravel'],
            'content' => implode('', [
                '<p>Blog Kit is an open source Laravel starter kit for building SEO-friendly blogs with Filament, Livewire and Tailwind CSS.</p>',
                '<p>It ships with per-record SEO controls, dynamic Open Graph images, author profiles, an FAQ system, and machine-readable discovery files for search and AI engines.</p>',
                '<p>The pages are SEO optimized and responsive. The process is simple: create a new Laravel project using the starter kit, set up your database, change the page designs as you like, and start writing blog posts.</p>',
                '<p>Read the <a href="https://laravel.com/docs">Laravel documentation</a>, explore <a href="https://livewire.laravel.com">Livewire</a> and <a href="https://filamentphp.com">Filament</a>, or review the source on <a href="https://github.com/achyutkneupane/Blog-Kit">GitHub</a>. Blog Kit is MIT licensed.</p>',
            ]),
        ]);

        $applySeo(
            $aboutPage,
            'About Blog Kit: Our Mission',
            'Learn who is behind Blog Kit and why we built an open source Laravel starter kit for fast, SEO-friendly blogging with the TALL stack.',
        );

        $faqPage = StaticPage::query()->updateOrCreate([
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

        $contactPage = StaticPage::query()->updateOrCreate([
            'slug' => 'contact',
            'type' => PageType::Contact,
        ], [
            'title' => 'Contact',
            'name' => 'contact',
            'description' => 'Questions, feedback or partnership ideas? Get in touch with the Blog Kit team.',
            'tags' => ['contact', 'support'],
            'content' => sprintf(
                '<p>Questions, feedback or partnership ideas? Email us at <a href="mailto:%1$s">%1$s</a> or open an issue on <a href="https://github.com/achyutkneupane/Blog-Kit/issues">GitHub</a>.</p>',
                $siteSettings->contact_email ?? 'hello@blogkit.test',
            ),
        ]);

        $applySeo(
            $contactPage,
            'Contact Blog Kit',
            'Get in touch with the Blog Kit team for questions, feedback, or partnership ideas about the Laravel blog starter kit.',
        );

        $faqs = [
            [
                'question' => 'What is Blog Kit?',
                'answer' => 'Blog Kit is an open source Laravel starter kit for building SEO-friendly blogs with Filament, Livewire, and Tailwind CSS. It ships with content models, metadata controls, structured data, author profiles, and generated social cards, so you can launch a publication without rebuilding the foundations.',
                'sort_order' => 1,
            ],
            [
                'question' => 'Do I need Node and Puppeteer?',
                'answer' => 'Only if you want the generated Open Graph cards. They are rendered with Browsershot through headless Chrome, so the server needs Node and Puppeteer. If you prefer to skip that dependency, set OG_IMAGE_ENABLED to false and the site falls back to uploaded or featured images.',
                'sort_order' => 2,
            ],
            [
                'question' => 'Which PHP and Laravel versions are supported?',
                'answer' => 'Blog Kit targets PHP 8.3 or newer and Laravel 13, together with Livewire 4 and Filament 5. The test suite runs on SQLite and MySQL, and the stack tracks the latest framework releases. Check composer.json for the exact version constraints before installing it in production.',
                'sort_order' => 3,
            ],
            [
                'question' => 'Can I customize the design?',
                'answer' => 'Yes. Every public page is built from Tailwind CSS utilities and Livewire single file components, so you can restyle layouts without touching PHP. Colors live as CSS custom properties and the admin panel has its own Filament theme. Edit or publish the Blade files to match your brand.',
                'sort_order' => 4,
            ],
            [
                'question' => 'How do I add an author profile?',
                'answer' => 'Open the user in the Filament admin panel and fill in the bio, job title, and website fields. Each user gets a slugged author page with Person and ProfilePage schema, links from every byline, a generated Open Graph card, and a sitemap entry once they publish an article.',
                'sort_order' => 5,
            ],
            [
                'question' => 'Is Blog Kit multilingual?',
                'answer' => 'The content model and SEO tooling are locale ready, but only English routes ship by default. To go multilingual, add locale prefixes to the routes, translate the static page content, and emit hreflang alternates through the SEO package. No database migrations are required for basic translations.',
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
