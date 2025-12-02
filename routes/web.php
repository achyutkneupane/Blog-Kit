<?php

declare(strict_types=1);

use App\Http\Controllers\RenderLandingPage;

Route::get('/', RenderLandingPage::class)
    ->name('landing-page');
