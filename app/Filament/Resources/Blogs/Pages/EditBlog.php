<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blogs\Pages;

use App\Enums\ScheduleOption;
use App\Filament\Resources\Blogs\BlogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $publishedAt = $data['published_at'];
        if ($publishedAt === null) {
            $data['publish'] = ScheduleOption::Draft;
        } elseif ($publishedAt > now()) {
            $data['publish'] = ScheduleOption::Later;
        } else {
            $data['publish'] = ScheduleOption::Now;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
