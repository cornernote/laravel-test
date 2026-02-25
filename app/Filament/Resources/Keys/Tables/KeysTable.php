<?php

namespace App\Filament\Resources\Keys\Tables;

use App\Filament\Resources\Keys\KeyResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class KeysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('locks_count')
                    ->label('Locks')
                    ->counts('locks')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->queryStringIdentifier('keys')
            ->recordUrl(fn($record) => KeyResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ViewAction::make()
                    ->url(fn($record) => KeyResource::getUrl('view', ['record' => $record])),
                EditAction::make()
                    ->url(fn($record) => KeyResource::getUrl('edit', ['record' => $record])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
