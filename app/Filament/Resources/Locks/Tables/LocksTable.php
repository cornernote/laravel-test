<?php

namespace App\Filament\Resources\Locks\Tables;

use App\Filament\Resources\Locks\LockResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('keys_count')
                    ->label('Keys')
                    ->counts('keys')
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
            ->recordUrl(fn($record) => LockResource::getUrl('view', ['record' => $record]))
            ->queryStringIdentifier('locks')
            ->recordActions([
                ViewAction::make()
                    ->url(fn($record) => LockResource::getUrl('view', ['record' => $record])),
                EditAction::make()
                    ->url(fn($record) => LockResource::getUrl('view', ['record' => $record])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
