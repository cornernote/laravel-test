<?php

namespace App\Filament\Resources\Locks\Pages;

use App\Filament\Resources\Locks\LockResource;
use App\Filament\Resources\Systems\SystemResource;
use App\Models\Lock;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLock extends EditRecord
{
    protected static string $resource = LockResource::class;

    public function getBreadcrumbs(): array
    {
        /** @var Lock $lock */
        $lock = $this->getRecord();
        $system = $lock->system;

        if (!$system) {
            return [
                LockResource::getUrl('index') => 'Locks',
                LockResource::getUrl('view', ['record' => $lock]) => $lock->title,
            ];
        }

        return [
            SystemResource::getUrl('index') => 'Systems',
            SystemResource::getUrl('view', ['record' => $system]) => $system->title,
            'Locks',
            LockResource::getUrl('view', ['record' => $lock]) => $lock->title,
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
