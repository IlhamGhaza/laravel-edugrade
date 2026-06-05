<?php

namespace App\Filament\Resources\MataPelajarans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use App\Models\MataPelajaran;

class MataPelajaranInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_mapel')
                    ->label('Kode Mapel'),
                TextEntry::make('nama_mapel')
                    ->label('Nama Mata Pelajaran'),
                TextEntry::make('gurus.nama_guru')
                    ->label('Guru Pengampu')
                    ->badge()
                    ->color('success'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (MataPelajaran $record): bool => $record->trashed()),
            ]);
    }
}
