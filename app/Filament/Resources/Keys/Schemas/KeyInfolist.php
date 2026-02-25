<?php

namespace App\Filament\Resources\Keys\Schemas;

use App\Filament\Resources\Keys\KeyResource;
use App\Filament\Resources\Locks\LockResource;
use App\Models\Key;
use App\Models\Lock;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class KeyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Opens Locks')
                    ->collapsible()
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('locks')
                            ->hiddenLabel()
                            ->columns()
                            ->schema([
                                TextEntry::make('title')
                                    ->size(TextSize::Large)
                                    ->weight(FontWeight::Bold)
                                    ->hiddenLabel()
                                    ->url(fn(string $state, Lock $record) => LockResource::getUrl('view', [
                                        'record' => $record->getKey(),
                                    ])),
                                Section::make('Opened By Keys')
                                    ->collapsible()
                                    ->collapsed()
                                    ->schema([
                                        RepeatableEntry::make('keys')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextEntry::make('title')
                                                    ->hiddenLabel()
                                                    ->url(fn(string $state, Key $record) => KeyResource::getUrl('view', [
                                                        'record' => $record->getKey(),
                                                    ]))
                                            ]),
                                    ]),
                            ]),
                    ]),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
