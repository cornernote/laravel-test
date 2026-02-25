<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // register vite for dev auto-refresh - also run `npm run dev`
        FilamentView::registerRenderHook(
            'panels::head.end',
            fn(): string => Blade::render(<<<'BLADE'
                @vite(['resources/css/app.css', 'resources/js/app.js'])
            BLADE
            ),
        );
    }
}
