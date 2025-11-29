<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seo_details', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('meta_title')->nullable();
            $blueprint->string('meta_description')->nullable();
            $blueprint->string('meta_keywords')
                ->nullable()
                ->default('[]');

            $blueprint->string('og_title')->nullable();
            $blueprint->string('og_description')->nullable();
            $blueprint->string('og_image')->nullable();
            $blueprint->string('og_url')->nullable();

            $blueprint->string('canonical')->nullable();
            $blueprint->string('robots')
                ->nullable()
                ->default('[]');
            $blueprint->string('author')->nullable();
            $blueprint->string('publisher')->nullable();
            $blueprint->json('schema')->nullable();
            $blueprint->morphs('seoable');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_details');
    }
};
