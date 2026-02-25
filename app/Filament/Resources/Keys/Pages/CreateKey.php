<?php

namespace App\Filament\Resources\Keys\Pages;

use App\Filament\Resources\Keys\KeyResource;
use App\Filament\Resources\Systems\SystemResource;
use App\Models\System;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\Url;

class CreateKey extends CreateRecord
{
    protected static string $resource = KeyResource::class;

    #[Url(as: 'system')]
    public int $system = 0;

    public function getBreadcrumbs(): array
    {
        $system = System::find($this->system);

        return [
            SystemResource::getUrl('index') => 'Systems',
            SystemResource::getUrl('view', ['record' => $system]) => $system->title,
            'Keys',
            'Create',
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        abort_unless($this->system, 400, 'Missing system.');

        $data['system_id'] = $this->system;

        return $data;
    }
}
