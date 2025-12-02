<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blogs\Schemas;

use App\Models\Blog;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

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
                        Grid::make(3)
                            ->columnSpanFull()
                            ->components([
                                TextEntry::make('categories.name')
                                    ->label('Category')
                                    ->badge(),
                                TextEntry::make('author.name')
                                    ->label('Author'),
                                IconEntry::make('is_featured')
                                    ->label('Featured')
                                    ->boolean(),
                            ]),
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
