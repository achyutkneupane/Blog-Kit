<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns()
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('category.name')
                            ->label('Category')
                            ->numeric(),
                        TextEntry::make('author.name')
                            ->label('Author')
                            ->numeric(),
                        TextEntry::make('title'),
                        TextEntry::make('published_at')
                            ->label('Published')
                            ->since()
                            ->dateTimeTooltip()
                            ->placeholder('Draft'),
                        TextEntry::make('description')
                            ->label('Excerpt')
                            ->columnSpanFull(),
                        TextEntry::make('content')
                            ->html()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
