<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update(
            'site.description',
            fn (string $description): string => 'The starter kit for the Laravel framework with blog features and Filament v5.',
        );

        $this->migrator->update('site.robots_txt', fn (string $robots): string => implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /pulse',
            '',
            'Sitemap: '.config('app.url').'/sitemap.xml',
        ]));
    }
};
