<?php

declare(strict_types=1);

Route::get('/', App\Http\Controllers\RenderLandingPage::class)
    ->name('landing-page');
