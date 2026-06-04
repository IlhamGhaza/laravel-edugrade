<?php

namespace App\Filament\Resources\Gurus;

use App\Filament\Resources\Gurus\Pages\CreateGuru;
use UnitEnum;
use App\Filament\Resources\Gurus\Pages\EditGuru;
use App\Filament\Resources\Gurus\Pages\ListGurus;
use App\Filament\Resources\Gurus\Pages\ViewGuru;
use App\Filament\Resources\Gurus\Schemas\GuruForm;
use App\Filament\Resources\Gurus\Schemas\GuruInfolist;
use App\Filament\Resources\Gurus\Tables\GurusTable;
use App\Models\Guru;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * Resource Filament untuk mengelola data Guru.
 *
 * Guru terdiri dari: ID Guru (unik), Nama Guru, Mata Pelajaran,
 * dan terhubung ke User melalui foreign key.
 */
class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static string|UnitEnum|null $navigationGroup = 'Data Master';

    protected static ?string $navigationLabel = 'Guru';

    protected static ?string $modelLabel = 'Guru';

    protected static ?string $pluralModelLabel = 'Guru';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return GuruForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GuruInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GurusTable::configure($table);
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
            'index' => ListGurus::route('/'),
            'create' => CreateGuru::route('/create'),
            'view' => ViewGuru::route('/{record}'),
            'edit' => EditGuru::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
