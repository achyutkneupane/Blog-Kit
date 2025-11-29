<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
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
    }
}
