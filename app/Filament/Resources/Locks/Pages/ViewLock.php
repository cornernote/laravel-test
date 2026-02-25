<?php

namespace App\Filament\Resources\Locks\Pages;

use App\Filament\Resources\Locks\LockResource;
use App\Filament\Resources\Systems\SystemResource;
use App\Models\Lock;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLock extends ViewRecord
{
    protected static string $resource = LockResource::class;

    public function getTitle(): string
    {
        /** @var Lock $lock */
        $lock = $this->getRecord();

        return 'Lock: ' . $lock->title ?? 'View Lock';
    }

    public function getBreadcrumbs(): array
    {
        /** @var Lock $lock */
        $lock = $this->getRecord();
        $system = $lock->system;

        if (!$system) {
            return parent::getBreadcrumbs();
        }

        return [
            SystemResource::getUrl('index') => 'Systems',
            SystemResource::getUrl('view', ['record' => $system]) => $system->title,
            'Locks',
            $lock->title,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
