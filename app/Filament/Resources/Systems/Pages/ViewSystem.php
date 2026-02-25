<?php

namespace App\Filament\Resources\Systems\Pages;

use App\Filament\Resources\Systems\SystemResource;
use App\Models\System;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewSystem extends ViewRecord
{
    protected static string $resource = SystemResource::class;

    public function getTitle(): string
    {
        /** @var System $system */
        $system = $this->getRecord();

        return 'System: ' . $system->title ?? 'View System';
    }

    public function getBreadcrumbs(): array
    {
        /** @var System $system */
        $system = $this->getRecord();

        return [
            SystemResource::getUrl('index') => 'Systems',
            $system->title,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ...static::getResource()::getWidgets(),
        ];
    }

    public function getWidgetData(): array
    {
        return [
            'system' => $this->getRecord(),
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}
