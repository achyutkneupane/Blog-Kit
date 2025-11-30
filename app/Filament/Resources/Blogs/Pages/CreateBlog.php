<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blogs\Pages;

use App\Enums\ScheduleOption;
use App\Filament\Resources\Blogs\BlogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $publishOption = ScheduleOption::tryFrom($data['publish']);

        if ($publishOption === ScheduleOption::Now) {
            $data['published_at'] = now();
        } elseif ($publishOption === ScheduleOption::Draft) {
            $data['published_at'] = null;
        }

        return $data;
    }
}
