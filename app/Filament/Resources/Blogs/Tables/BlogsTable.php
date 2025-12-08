<?php

declare(strict_types=1);

namespace App\Filament\Resources\Blogs\Tables;

use App\Filament\Components\SEOAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                TextColumn::make('categories.name')
                    ->label('Category')
                    ->badge(),
                TextColumn::make('author.name'),
                TextColumn::make('published_at')
                    ->label('Published')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
                TextColumn::make('views_count')
                    ->label('Views')
                    ->counts('views')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                SEOAction::make(),
                EditAction::make(),
            ]);
    }
}
