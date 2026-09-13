<?php

declare(strict_types=1);

use App\Models\Blog;
use App\OGImage\OGImageGenerator;
use Illuminate\Support\Facades\Storage;

it('renders a pixel perfect card with browsershot', function (): void {
    if (! env('RUN_OG_RENDER')) {
        $this->markTestSkipped('Set RUN_OG_RENDER=1 to run the real Browsershot render.');
    }

    Storage::fake('local');

    $blog = Blog::query()->create([
        'title' => 'Rendering Open Graph images with Browsershot',
        'description' => 'A real render smoke test.',
        'content' => '<p>Body</p>',
        'tags' => ['laravel'],
        'user_id' => null,
        'published_at' => now()->subDay(),
    ]);

    $path = app(OGImageGenerator::class)->generate($blog, force: true);

    $absolutePath = Storage::disk('local')->path($path);

    expect(is_file($absolutePath))->toBeTrue();

    [$width, $height] = getimagesize($absolutePath);

    expect($width)->toBe(2400)
        ->and($height)->toBe(1260);
});
