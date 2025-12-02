<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blogs\Schemas;

use App\Enums\ScheduleOption;
use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Blog;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('title')
                            ->required(),
                        TextInput::make('slug')
                            ->disabledOn('create')
                            ->helperText('Will auto-generate while creating the blog.'),
                        Select::make('categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm(
                                fn (Schema $schema): Schema => CategoryResource::form($schema)
                            )
                            ->required(),
                    ]),
                Textarea::make('description')
                    ->label('Excerpt')
                    ->required()
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                Group::make([
                    ToggleButtons::make('publish')
                        ->label('Publish')
                        ->options(ScheduleOption::class)
                        ->live()
                        ->inline(),
                    DateTimePicker::make('published_at')
                        ->label('Publish On')
                        ->visible(fn (callable $get): bool => $get('publish') === ScheduleOption::Later)
                        ->required(fn (callable $get): bool => $get('publish') === ScheduleOption::Later),
                ])
                    ->columns()
                    ->columnSpanFull()
                    ->hidden(fn (?Blog $blog): bool => $blog && $blog->published_at < now()),
                ToggleButtons::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->inline()
                    ->options([
                        1 => 'Yes',
                        0 => 'No',
                    ])
                    ->default(0),
            ]);
    }
}
