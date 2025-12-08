<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\StaticPage;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->isLocal()) {
            User::query()->firstOrCreate([
                'email' => 'developer@test.com',
            ], [
                'name' => 'Blog Developer',
                'role' => UserRole::Developer,
                'password' => bcrypt('password'),
            ]);

            User::query()->firstOrCreate([
                'email' => 'admin@test.com',
            ], [
                'name' => 'Blog Admin',
                'role' => UserRole::Admin,
                'password' => bcrypt('password'),
            ]);

            User::query()->firstOrCreate([
                'email' => 'writer@test.com',
            ], [
                'name' => 'Blog Writer',
                'role' => UserRole::Writer,
                'password' => bcrypt('password'),
            ]);

            User::query()->firstOrCreate([
                'email' => 'user@test.com',
            ], [
                'name' => 'Blog User',
                'role' => UserRole::User,
                'password' => bcrypt('password'),
            ]);
        }

        StaticPage::query()->firstOrCreate([
            'slug' => 'landing-page',
        ], [
            'title' => 'Home',
        ]);
        StaticPage::query()->firstOrCreate([
            'slug' => 'about-us',
        ], [
            'title' => 'About Us',
            'content' => implode('', [
                '<p>Welcome to the Blog Kit! This is a simple starter kit for building a blog using Laravel and Tailwind CSS.</p>',
                '<p>The pages are SEO optimized and responsive. The process is simple: create a new laravel project using the starter kit, set up your database, change the page designs as you like, and start writing blog posts!</p>',
                '<p>Feel free to contribute to the project on <a href="https://github.com/achyutkneupane/Blog-Kit">GitHub</a> or reach out to me on <a href="https://www.linkedin.com/in/achyutneupane">LinkedIn</a>.</p>',
                '<p>Happy blogging!</p>',
            ]),
        ]);
    }
}
