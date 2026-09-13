<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PageType;
use App\Enums\UserRole;
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
    }
}
