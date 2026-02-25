<?php

namespace App\Filament\Resources\Locks\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class LockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Hidden::make('system_id')
                    ->default(fn($record) => $record?->system_id ?? request()->integer('system'))
                    ->dehydrated(),
                Section::make('Keys')
                    ->schema([
                        CheckboxList::make('keys')
                            ->relationship(
                                name: 'keys',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn($query, Get $get, $record) => $query->where(
                                    'system_id',
                                    $record?->system_id ?? $get('system_id'),
                                ),
                            )
                            ->searchable()
                            ->columns(2)
                            ->bulkToggleable()
                            ->helperText('Select which keys open this lock.'),
                    ]),
            ]);
    }
}
