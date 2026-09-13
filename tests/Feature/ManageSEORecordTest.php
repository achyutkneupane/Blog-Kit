<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Filament\Resources\Blogs\Pages\ManageBlogSEO;
use App\Models\Blog;
use App\Models\User;
use Livewire\Livewire;

it('persists seo overrides against the model morph columns', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Developer]);

    $this->actingAs($admin);

    $blog = Blog::query()->create([
        'title' => 'SEO managed blog',
        'description' => 'Description',
        'content' => '<p>Body</p>',
        'tags' => [],
        'user_id' => $admin->getKey(),
        'published_at' => now()->subDay(),
    ]);

    Livewire::test(ManageBlogSEO::class, ['record' => $blog->slug])
        ->fillForm(['meta_title' => 'Custom SEO title'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($blog->refresh()->seo?->meta_title)->toBe('Custom SEO title');
});
