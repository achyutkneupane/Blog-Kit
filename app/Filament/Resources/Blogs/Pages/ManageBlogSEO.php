<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blogs\Pages;

use App\Filament\Components\ManageSEORecord;
use App\Filament\Resources\Blogs\BlogResource;

final class ManageBlogSEO extends ManageSEORecord
{
    protected static string $resource = BlogResource::class;
}
