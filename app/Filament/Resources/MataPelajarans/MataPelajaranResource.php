<?php

namespace App\Filament\Resources\MataPelajarans;

use App\Filament\Resources\MataPelajarans\Pages\CreateMataPelajaran;
use App\Filament\Resources\MataPelajarans\Pages\EditMataPelajaran;
use App\Filament\Resources\MataPelajarans\Pages\ListMataPelajarans;
use App\Filament\Resources\MataPelajarans\Pages\ViewMataPelajaran;
use App\Filament\Resources\MataPelajarans\Schemas\MataPelajaranForm;
use App\Filament\Resources\MataPelajarans\Schemas\MataPelajaranInfolist;
use App\Filament\Resources\MataPelajarans\Tables\MataPelajaransTable;
use App\Models\MataPelajaran;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MataPelajaranResource extends Resource
{
    protected static ?string $model = MataPelajaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Data Master';

    protected static ?string $navigationLabel = 'Mata Pelajaran';

    protected static ?string $modelLabel = 'Mata Pelajaran';

    protected static ?string $pluralModelLabel = 'Mata Pelajaran';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return MataPelajaranForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MataPelajaranInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MataPelajaransTable::configure($table);
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
            'index' => ListMataPelajarans::route('/'),
            'create' => CreateMataPelajaran::route('/create'),
            'view' => ViewMataPelajaran::route('/{record}'),
            'edit' => EditMataPelajaran::route('/{record}/edit'),
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
