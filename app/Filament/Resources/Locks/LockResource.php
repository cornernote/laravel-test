<?php

namespace App\Filament\Resources\Locks;

use App\Filament\Resources\Locks\Pages\CreateLock;
use App\Filament\Resources\Locks\Pages\EditLock;
use App\Filament\Resources\Locks\Pages\ListLocks;
use App\Filament\Resources\Locks\Pages\ViewLock;
use App\Filament\Resources\Locks\Schemas\LockForm;
use App\Filament\Resources\Locks\Schemas\LockInfolist;
use App\Filament\Resources\Locks\Tables\LocksTable;
use App\Models\Lock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LockResource extends Resource
{
    protected static ?string $model = Lock::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return LockForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LockInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocks::route('/'),
            'create' => CreateLock::route('/create'),
            'view' => ViewLock::route('/{record}'),
            'edit' => EditLock::route('/{record}/edit'),
        ];
    }
}
