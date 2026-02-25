<?php

namespace App\Filament\Resources\Keys\Pages;

use App\Filament\Resources\Keys\KeyResource;
use App\Filament\Resources\Systems\SystemResource;
use App\Models\Key;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKey extends EditRecord
{
    protected static string $resource = KeyResource::class;

    public function getBreadcrumbs(): array
    {
        /** @var Key $key */
        $key = $this->getRecord();
        $system = $key->system;

        if(!$system) {
            return [
                keyResource::getUrl('index') => 'Keys',
                KeyResource::getUrl('view', ['record' => $key]) => $key->title,
            ];
        }

        return [
            SystemResource::getUrl('index') => 'Systems',
            SystemResource::getUrl('view', ['record' => $system]) => $system->title,
            'Keys',
            KeyResource::getUrl('view', ['record' => $key]) => $key->title,
            'Edit',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
