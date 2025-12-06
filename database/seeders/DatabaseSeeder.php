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
            User::factory()->create([
                'name' => 'Blog Developer',
                'email' => 'developer@test.com',
                'role' => UserRole::Developer,
                'password' => bcrypt('password'),
            ]);

            User::factory()->create([
                'name' => 'Blog Admin',
                'email' => 'admin@test.com',
                'role' => UserRole::Admin,
                'password' => bcrypt('password'),
            ]);

            User::factory()->create([
                'name' => 'Blog Writer',
                'email' => 'writer@test.com',
                'role' => UserRole::Writer,
                'password' => bcrypt('password'),
            ]);

            User::factory()->create([
                'name' => 'Blog User',
                'email' => 'user@test.com',
                'role' => UserRole::User,
                'password' => bcrypt('password'),
            ]);
        }

        StaticPage::create([
            'title' => 'Home',
            'slug' => 'landing-page',
        ]);
        StaticPage::create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'content' => implode('', [
                '<p>Welcome to the Blog Kit! This is a simple starter kit for building a blog using Laravel and Tailwind CSS.</p>',
                '<p>The pages are SEO optimized and responsive. The process is simple: create a new laravel project using the starter kit, set up your database, change the page designs as you like, and start writing blog posts!</p>',
                '<p>Feel free to contribute to the project on <a href="https://github.com/achyutkneupane/Blog-Kit">GitHub</a> or reach out to me on <a href="https://www.linkedin.com/in/achyutneupane">LinkedIn</a>.</p>',
                '<p>Happy blogging!</p>',
            ]),
        ]);
    }
}
