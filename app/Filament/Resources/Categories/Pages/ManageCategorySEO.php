<?php

declare(strict_types=1);

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Components\ManageSEORecord;
use App\Filament\Resources\Categories\CategoryResource;

final class ManageCategorySEO extends ManageSEORecord
{
    protected static string $resource = CategoryResource::class;
}
