<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Controllers\RobotsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class SeoServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::get('/robots.txt', RobotsController::class)->name('robots');
    }
}
