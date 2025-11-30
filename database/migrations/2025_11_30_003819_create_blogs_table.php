<?php

declare(strict_types=1);

use App\Models\BlogCategory;
use App\Models\User;
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
        Schema::create('blogs', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('title');
            $blueprint->string('slug');
            $blueprint->text('description');
            $blueprint->longText('content');
            $blueprint->foreignIdFor(BlogCategory::class)
                ->constrained();
            $blueprint->foreignIdFor(User::class)
                ->constrained();
            $blueprint->timestamp('published_at')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
