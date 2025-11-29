<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.name', 'Blog Kit');
        $this->migrator->add('site.description', 'The starter kit for the Laravel framework with blog features and filament v4.');
        $this->migrator->add('site.logo', '');
        $this->migrator->add('site.favicon', '');
        $this->migrator->add('site.og_image', '');
        $this->migrator->add('site.header_scripts', '');
        $this->migrator->add('site.footer_scripts', '');
        $this->migrator->add('site.robots_txt', '');
    }
};
