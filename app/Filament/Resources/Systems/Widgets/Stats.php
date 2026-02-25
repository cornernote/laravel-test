<?php

namespace App\Filament\Resources\Systems\Widgets;

use App\Models\Key;
use App\Models\Lock;
use App\Models\System;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Stats extends StatsOverviewWidget
{
    protected string $view = 'filament.resources.systems.widgets.stats';

    public ?System $system = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Keys', $this->system?->keys()->count() ?? Key::count())
                ->description($this->system ? 'Keys in this system' : 'Total keys'),

            Stat::make('Locks', $this->system?->locks()->count() ?? Lock::count())
                ->description($this->system ? 'Locks in this system' : 'Total locks'),
        ];
    }
}
