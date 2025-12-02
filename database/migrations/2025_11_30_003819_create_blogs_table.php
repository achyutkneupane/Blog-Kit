<?php

declare(strict_types=1);
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
            $blueprint->foreignIdFor(User::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $blueprint->tinyInteger('is_featured')->default(0);
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
