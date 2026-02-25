<?php

namespace App\Filament\Resources\Systems\Pages;

use App\Filament\Resources\Systems\SystemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSystems extends ListRecords
{
    protected static string $resource = SystemResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ...static::getResource()::getWidgets(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
