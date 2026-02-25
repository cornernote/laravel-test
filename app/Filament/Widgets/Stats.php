<?php

namespace App\Filament\Widgets;

use App\Models\Key;
use App\Models\Lock;
use App\Models\System;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Stats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Systems', System::query()->count())
                ->description('Total systems'),
            Stat::make('Keys', Key::query()->count())
                ->description('Total keys'),
            Stat::make('Locks', Lock::query()->count())
                ->description('Total locks'),
        ];
    }
}
