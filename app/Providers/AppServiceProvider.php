<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Vite::macro('image', fn (string $asset) => $this->asset("resources/assets/images/{$asset}"));

        Table::configureUsing(
            fn (Table $table): Table => $table
                ->defaultNumberLocale('en_NP')
                ->defaultCurrency('NPR')
        );

        if (app()->environment('production')) {
            URL::useOrigin(config('app.url'));
            URL::forceScheme('https');
        }

        TextEntry::configureUsing(
            fn (TextEntry $textEntry): TextEntry => $textEntry
                ->placeholder('N/A')
        );

        TextColumn::configureUsing(
            fn (TextColumn $textColumn): TextColumn => $textColumn
                ->placeholder('N/A')
        );

        FileUpload::configureUsing(
            fn (FileUpload $fileUpload): FileUpload => $fileUpload
                ->preserveFilenames()
                ->disk('public')
                ->helperText('Please keep the aspect ratio in mind since cropping may occur. Press the pencil icon to edit the image after uploading.')
                ->visibility('public')
        );

        ImageColumn::configureUsing(
            fn (ImageColumn $imageColumn): ImageColumn => $imageColumn
                ->disk('public')
                ->visibility('public')
        );

        ImageEntry::configureUsing(
            fn (ImageEntry $imageEntry): ImageEntry => $imageEntry
                ->disk('public')
                ->visibility('public')
        );
    }
}
