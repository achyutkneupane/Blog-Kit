<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;

it('returns a redirection response', function (): void {
    $response = $this->get('/admin');

    $response->assertStatus(302);
});
