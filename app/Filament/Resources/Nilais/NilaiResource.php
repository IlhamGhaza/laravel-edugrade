<?php

namespace App\Filament\Resources\Nilais;

use App\Filament\Resources\Nilais\Pages\CreateNilai;
use UnitEnum;
use App\Filament\Resources\Nilais\Pages\EditNilai;
use App\Filament\Resources\Nilais\Pages\ListNilais;
use App\Filament\Resources\Nilais\Pages\ViewNilai;
use App\Filament\Resources\Nilais\Schemas\NilaiForm;
use App\Filament\Resources\Nilais\Schemas\NilaiInfolist;
use App\Filament\Resources\Nilais\Tables\NilaisTable;
use App\Models\Nilai;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Resource Filament untuk mengelola data Nilai.
 *
 * - Admin/Super Admin: lihat semua nilai
 * - Guru: lihat & kelola nilai yang dia input saja
 * - Siswa: tidak punya akses ke resource ini (lihat via LaporanNilai page)
 */
class NilaiResource extends Resource
{
    protected static ?string $model = Nilai::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Nilai Siswa';

    protected static ?string $modelLabel = 'Nilai';

    protected static ?string $pluralModelLabel = 'Nilai';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return NilaiForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NilaiInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NilaisTable::configure($table);
    }

    /**
     * Scope query berdasarkan role:
     * - Guru hanya melihat nilai yang dia input
     * - Admin melihat semua
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->hasRole('guru')) {
            $guru = \App\Models\Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $query->where('guru_id', $guru->id);
            }
        }

        return $query;
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
            'index' => ListNilais::route('/'),
            'create' => CreateNilai::route('/create'),
            'view' => ViewNilai::route('/{record}'),
            'edit' => EditNilai::route('/{record}/edit'),
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
