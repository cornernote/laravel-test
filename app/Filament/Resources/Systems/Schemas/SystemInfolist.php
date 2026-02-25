<?php

namespace App\Filament\Resources\Systems\Schemas;

use App\Filament\Resources\Systems\Livewire\KeysList;
use App\Filament\Resources\Systems\Livewire\LocksList;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SystemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('created_at')->dateTime()->placeholder('-'),
                TextEntry::make('updated_at')->dateTime()->placeholder('-'),

                Section::make()
                    ->columns()
                    ->columnSpanFull()
                    ->schema([
                        Livewire::make(KeysList::class, fn($record) => [
                            'system' => $record,
                        ]),
                        Livewire::make(LocksList::class, fn($record) => [
                            'system' => $record,
                        ]),
                    ]),
            ]);
    }
}
