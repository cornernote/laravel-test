<?php

namespace App\Filament\Resources\Systems\Livewire;

use App\Filament\Resources\Keys\KeyResource;
use App\Filament\Resources\Keys\Tables\KeysTable;
use App\Models\System;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class KeysList extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public System $system;

    public function table(Table $table): Table
    {
        return KeysTable::configure($table->query($this->system->keys()->getQuery()))
            ->headerActions([
                CreateAction::make()
                    ->url(fn() => KeyResource::getUrl('create', [
                        'system' => $this->system->getKey(),
                    ])),
            ]);
    }

    public function render(): View
    {
        return view('filament.resources.systems.livewire.keys-list');
    }
}
