<?php

namespace App\Filament\Resources\Keys\Pages;

use App\Filament\Resources\Keys\KeyResource;
use App\Filament\Resources\Systems\SystemResource;
use App\Models\Key;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKey extends ViewRecord
{
    protected static string $resource = KeyResource::class;

    public function getTitle(): string
    {
        /** @var Key $key */
        $key = $this->getRecord();

        return 'Key: ' . $key->title ?? 'View Key';
    }

    public function getBreadcrumbs(): array
    {
        /** @var Key $key */
        $key = $this->getRecord();
        $system = $key->system;

        if (!$system) {
            return [
                keyResource::getUrl('index') => 'Keys',
                $key->title,
            ];
        }

        return [
            SystemResource::getUrl('index') => 'Systems',
            SystemResource::getUrl('view', ['record' => $system]) => $system->title,
            'Keys',
            $key->title,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
