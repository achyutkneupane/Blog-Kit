<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaticPages;

use App\Filament\Components\SEOAction;
use App\Filament\Resources\StaticPages\Pages\ManageStaticPages;
use App\Filament\Resources\StaticPages\Pages\ManageStaticPageSEO;
use App\Models\StaticPage;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class StaticPageResource extends Resource
{
    protected static ?string $model = StaticPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentDuplicate;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->hidden(fn (StaticPage $staticPage): bool => $staticPage->slug === 'landing-page')
                    ->visibleOn('edit')
                    ->required(),
                RichEditor::make('content')
                    ->hidden(fn (StaticPage $staticPage): bool => $staticPage->slug === 'landing-page')
                    ->helperText('Skip if not a content page'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextEntry::make('title')
                    ->inlineLabel(),
                TextEntry::make('slug')
                    ->inlineLabel().
                TextEntry::make('content')
                    ->inlineLabel()
                    ->html(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('views_count')
                    ->label('Views')
                    ->counts('views')
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->slideOver(),
                SEOAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStaticPages::route('/'),
            'seo' => ManageStaticPageSEO::route('/{record}/seo'),
        ];
    }
}
