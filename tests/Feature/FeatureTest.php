<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('redirects to login page when filament is requested being guest', function (): void {
    $response = $this->get('/admin');

    $response->assertStatus(302);
    $response->assertRedirect('/admin/login');
});
